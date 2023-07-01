<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\CourseRate;
use App\Entity\CourseRegistration;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ParentFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $parents = $this->getParents($users);
        $students = $this->getStudents($users);

        foreach ($students as $student)
        {
            $student->setParent($parents[array_rand($parents)]);
            $manager->persist($student);
        }

        $manager->flush();
    }

    private function getParents(array $users): array
    {
        return array_values(
            array_filter($users, function (User $user) {
                return $user->hasRole(Role::Parent->value);
            })
        );
    }

    private function getStudents(array $users): array
    {
        return array_values(
            array_filter($users, function (User $user) {
                return $user->hasRole(Role::Student->value);
            })
        );
    }
}
