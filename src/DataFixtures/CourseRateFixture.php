<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\CourseRate;
use App\Entity\CourseRegistration;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseRateFixture extends Fixture
{
    const NUM_OF_RATES_PER_USER = 20;

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $courses = $manager->getRepository(Course::class)->findAll();
        $courseRates = $this->getCourseRates($this->getNonTeachers($users), $courses);

        foreach ($courseRates as $courseRate)
            $manager->persist($courseRate);

        $manager->flush();
    }

    private function getNonTeachers(array $users): array
    {
        return array_values(
            array_filter($users, function (User $user) {
                return !$user->hasRole(Role::Teacher->value);
            })
        );
    }

    private function getCourseRates(array $users, array $courses): \Generator
    {
        $courseIndexes = range(1, BasicFixture::NUM_OF_COURSES);
        foreach ($users as $user) {
            shuffle($courseIndexes);
            for ($index = 0; $index < self::NUM_OF_RATES_PER_USER; $index++)
            {
                $courseRate = new CourseRate();
                $courseRate->setRater($user);
                $courseRate->setCourse($courses[$index]);
                $courseRate->setRate(rand(1, 5));

                yield $courseRate;
            }
        }
    }
}
