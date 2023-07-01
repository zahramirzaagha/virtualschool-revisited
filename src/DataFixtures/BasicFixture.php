<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Role;
use App\Entity\School;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BasicFixture extends Fixture
{
    const NUM_OF_USERS = 20;
    const NUM_OF_COURSES = 20;

    public function load(ObjectManager $manager): void
    {
        $users = iterator_to_array($this->getUsers());
        foreach ($users as $user)
            $manager->persist($user);

        foreach ($this->getCourses($users) as $course)
            $manager->persist($course);

        $manager->flush();
    }

    private function getEmail(string $name): string
    {
        $nameExploded = explode(' ', $name);
        return strtolower($nameExploded[0]).'.'.strtolower($nameExploded[1]).'@gmail.com';
    }

    private function getRandomSchoolString(): string
    {
        $schoolArray = array_map(function (School $school) {
            return $school->value;
        }, School::cases());

        return $schoolArray[array_rand($schoolArray)];
    }

    private function getRandomRoleString(): string
    {
        $roleArray = array_map(function (Role $role) {
            return $role->value;
        }, Role::cases());
        $roleArrayFiltered = array_values(
            array_filter($roleArray, function (string $role) {
                return Role::Rater->value != $role;
            })
        );

        return $roleArrayFiltered[array_rand($roleArrayFiltered)];
    }

    private function getUsers(): \Generator
    {
        $names = array("Lucille Ritchie", "Cyril Ryan", "Joyce Lariviere", "Kristine Tanguay", "Marjorie Richards", "Olivia Crawford", "Alicia Bouffard", "Jean-Francois Michaud", "Carrie Day", "Adrian Clark", "Pete Pratt", "Margo Emond", "Jared Martineau", "Jean-Philippe Tessier", "Kay Rodrigue", "Graham Harding", "Duane Brown", "Mandy Legault", "Ivy Schultz", "Ingrid Skinner");
        foreach ($names as $name)
        {
            $user = new User();
            $user->setName($name);
            $user->setEmail($this->getEmail($name));
            $user->setSchool($this->getRandomSchoolString());
            $user->setPassword("$2y$13$6ACfLHCivlArZrYfez3WROSkb.eAjKL.h6milLLRzjK5CuxqWLlgG");
            $user->setRoles([$this->getRandomRoleString()]);
            $user->setIsVerified(true);

            yield $user;
        }
    }

    private function getCourses(Array $users): \Generator
    {
        $teachers = array_values(
            array_filter($users, function (User $user) {
                return $user->hasRole(Role::Teacher->value);
            })
        );
        $courseNames = array("Introduction to Algorithms and Data Structures", "Web Development with HTML, CSS, and JavaScript", "Database Systems and SQL Fundamentals", "Machine Learning and Artificial Intelligence", "Network Security and Cryptography", "Operating Systems: Concepts and Design", "Software Engineering Principles and Practices", "Introduction to Computer Graphics and Visualization", "Cybersecurity and Ethical Hacking", "Data Mining and Big Data Analytics", "Mobile Application Development for iOS and Android", "Cloud Computing and Virtualization Technologies", "Computer Organization and Architecture", "Human-Computer Interaction and User Experience Design", "Introduction to Robotics and Autonomous Systems", "Software Testing and Quality Assurance", "Introduction to Natural Language Processing", "Internet of Things: Concepts and Applications", "Parallel and Distributed Computing", "Computer Networks and Communication Protocols");
        foreach ($courseNames as $courseName)
        {
            $course = new Course();
            $course->setTitle($courseName);
            $course->setInstructor($this->getRandomTeacher($teachers));

            yield $course;
        }
    }

    private function getRandomTeacher(Array $teachers): User
    {
        return $teachers[array_rand($teachers)];
    }
}
