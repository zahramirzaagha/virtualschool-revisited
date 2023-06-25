<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("fake0@gmail.com");
        $user->setPassword("$2y$13$6ACfLHCivlArZrYfez3WROSkb.eAjKL.h6milLLRzjK5CuxqWLlgG");
        $manager->persist($user);

        $manager->flush();
    }
}
