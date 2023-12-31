<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024)]
    private ?string $name = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $school = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'children')]
    private ?User $parent = null;

    #[OneToMany(mappedBy: 'parent', targetEntity: User::class)]
    private Collection $children;

    #[OneToMany(mappedBy: 'instructor', targetEntity: Course::class)]
    private Collection $courses;

    #[OneToMany(mappedBy: 'student', targetEntity: CourseRegistration::class)]
    private Collection $registrations;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): static
    {
        $this->school = $school;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRolesString(): string
    {
        if (count($this->roles) == 1)
            return Role::from($this->roles[0])->name;

        if (count($this->roles) > 1)
            return implode(' and a ', array_map(function (string $roleString) {
                return Role::from($roleString)->name;
            }, $this->roles));

        return 'visitor';
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): static
    {
        $this->parent = $parent;
        $parent->addChildren($this);

        return $this;
    }

    private function addChildren(User $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
        }

        return $this;
    }

    public function getChildren(): Array
    {
        return $this->children->toArray();
    }

    public function removeChildren(User $child): static
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // Set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function addRegistration(CourseRegistration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
        }

        return $this;
    }

    public function removeRegistration(CourseRegistration $registration): static
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // Set the owning side to null (unless already changed)
            if ($registration->getStudent() === $this) {
                $registration->setStudent(null);
            }
        }

        return $this;
    }

    public function getRegistrations(): Array
    {
        return $this->registrations->toArray();
    }

    public function getGpa(): ?float
    {
        if ($this->registrations->count() == 0)
            return null;

        return $this->registrations->reduce(function(int $sum, CourseRegistration $x): int {
                return $sum + $x->getGrade();
            }, 0) / $this->registrations->count();
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
            // Set the owning side to null (unless already changed)
            if ($course->getInstructor() === $this) {
                $course->setInstructor(null);
            }
        }

        return $this;
    }
}
