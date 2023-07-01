<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\CourseRegistration;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseRegistrationFixture extends Fixture
{
    const NUM_OF_REGS_PER_USER = 10;

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $courses = $manager->getRepository(Course::class)->findAll();
        $courseRegistrations = $this->getCourseRegistrations($this->getStudents($users), $courses);

        foreach ($courseRegistrations as $courseRegistration)
            $manager->persist($courseRegistration);

        $manager->flush();
    }

    private function getStudents(array $users): array
    {
        return array_values(
            array_filter($users, function (User $user) {
                return $user->hasRole(Role::Student->value);
            })
        );
    }

    private function getCourseRegistrations(array $users, array $courses): \Generator
    {
        $courseIndexes = range(1, BasicFixture::NUM_OF_COURSES);
        foreach ($users as $user) {
            shuffle($courseIndexes);
            for ($index = 0; $index < self::NUM_OF_REGS_PER_USER; $index++)
            {
                $courseRegistration = new CourseRegistration();
                $courseRegistration->setStudent($user);
                $courseRegistration->setCourse($courses[$index]);
                $courseRegistration->setGrade($this->getRandomFloat());

                yield $courseRegistration;
            }
        }
    }

    function getRandomFloat($min = 50, $max = 100) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
}
