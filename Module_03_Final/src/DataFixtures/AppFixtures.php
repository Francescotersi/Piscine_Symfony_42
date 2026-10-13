<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Appfixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
         $usersData = [
            ['username' => 'test1', 'password' => 'test1'],
            ['username' => 'test2', 'password' => 'test2'],
        ];

        foreach ($usersData as $user) {
            $newUser = new User();
            $newUser->setUsername($user['username']);
            $hashedPassword = $this->passwordHasher->hashPassword($newUser, $user['password']);
            $newUser->setPassword($hashedPassword);
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}
