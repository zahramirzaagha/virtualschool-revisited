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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $instructor = null;

    #[OneToMany(mappedBy: 'course', targetEntity: CourseRate::class)]
    private Collection $rates;

    public function __construct()
    {
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
