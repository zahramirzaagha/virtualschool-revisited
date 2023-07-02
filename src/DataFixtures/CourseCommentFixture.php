<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\CourseRate;
use App\Entity\CourseRegistration;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseCommentFixture extends Fixture
{
    const NUM_OF_COMMENTS_PER_USER = 20;
    const LOREM_IPSUM = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $courses = $manager->getRepository(Course::class)->findAll();
        $courseComments = $this->getCourseComments($this->getNonTeachers($users), $courses);

        foreach ($courseComments as $courseComment)
            $manager->persist($courseComment);

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

    private function getCourseComments(array $users, array $courses): \Generator
    {
        $courseIndexes = range(1, BasicFixture::NUM_OF_COURSES);
        foreach ($users as $user) {
            shuffle($courseIndexes);
            for ($index = 0; $index < self::NUM_OF_COMMENTS_PER_USER; $index++)
            {
                $courseComment = new Comment();
                $courseComment->setUser($user);
                $courseComment->setCourse($courses[$index]);
                $courseComment->setText($this->getLoremIpsum());

                yield $courseComment;
            }
        }
    }

    private function getLoremIpsum(): string
    {
        $length = rand(20, 70);
        return substr(self::LOREM_IPSUM, 0, $length);
    }
}
