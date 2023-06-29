<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2048)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $instructor = null;

    #[OneToMany(mappedBy: 'course', targetEntity: CourseRegistration::class)]
    private Collection $registrations;

    #[OneToMany(mappedBy: 'course', targetEntity: CourseRate::class)]
    private Collection $rates;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getInstructor(): ?User
    {
        return $this->instructor;
    }

    public function setInstructor(?User $instructor): static
    {
        $this->instructor = $instructor;

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
            if ($registration->getCourse() === $this) {
                $registration->setCourse(null);
            }
        }

        return $this;
    }

    public function getRegistrations(): Array
    {
        return $this->registrations->toArray();
    }

    public function isRegistered(int $userId): ?bool
    {
        $registration = $this->registrations->findFirst(function (int $key, CourseRegistration $x) use ($userId) {
            return $userId == $x->getStudent()->getId();
        });
        if (null == $registration)
            return false;

        return true;
    }

    public function getClassAverage(): ?float
    {
        if ($this->registrations->count() == 0)
            return null;

        return $this->registrations->reduce(function(int $sum, CourseRegistration $x): int {
                return $sum + $x->getGrade();
            }, 0) / $this->registrations->count();
    }

    public function addRate(CourseRate $rate): static
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
        }

        return $this;
    }

    public function removeRate(CourseRate $rate): static
    {
        if ($this->rates->contains($rate)) {
            $this->rates->removeElement($rate);
            // Set the owning side to null (unless already changed)
            if ($rate->getCourse() === $this) {
                $rate->setCourse(null);
            }
        }

        return $this;
    }

    public function getAverageRate(): float
    {
        if ($this->rates->count() == 0)
            return 0;

        return $this->rates->reduce(function(int $sum, CourseRate $x): int {
            return $sum + $x->getRate();
        }, 0) / $this->rates->count();
    }

    public function getCourseRate(int $userId): ?int
    {
        $rate = $this->rates->findFirst(function(int $key, CourseRate $x) use ($userId): bool {
            return $userId === $x->getRater()->getId();
        }) ;
        if (null == $rate)
            return 0;

        return $rate->getRate();
    }
}
