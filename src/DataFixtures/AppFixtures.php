<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teacher = new User();
        $teacher->setEmail("teacher@gmail.com");
        $teacher->setPassword("$2y$13$6ACfLHCivlArZrYfez3WROSkb.eAjKL.h6milLLRzjK5CuxqWLlgG");
        $teacher->setRoles([Role::Teacher->value]);
        $teacher->setIsVerified(true);
        $manager->persist($teacher);

        $parent = new User();
        $parent->setEmail("parent@gmail.com");
        $parent->setPassword("$2y$13$6ACfLHCivlArZrYfez3WROSkb.eAjKL.h6milLLRzjK5CuxqWLlgG");
        $parent->setRoles([Role::Parent->value]);
        $parent->setIsVerified(true);
        $manager->persist($parent);

        $student = new User();
        $student->setEmail("student@gmail.com");
        $student->setPassword("$2y$13$6ACfLHCivlArZrYfez3WROSkb.eAjKL.h6milLLRzjK5CuxqWLlgG");
        $student->setRoles([Role::Student->value]);
        $student->setIsVerified(true);
        $manager->persist($student);

        $manager->flush();
    }
}
