<?php

namespace App\E07Bundle\DataFixtures;

use App\E03Bundle\Entity\Post;
use App\Entity\User;
use App\E02Bundle\Entity\Admin;
use App\E05Bundle\Entity\PostVote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DataFixtures extends Fixture {

private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void {
        $admin = new Admin();
        $admin->setUsername('admin');
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $manager->persist($admin);

        $allPosts = [];

        $user = new User();
        $user->setUsername('9reputation');
        $password = $this->hasher->hashPassword($user, '9reputation');
        $user->setPassword($password);
        $manager->persist($user);

        for ($i = 0; $i < 9; $i++) {
            $post = new Post();
            $post->setTitle('9reputation ' . ($i + 1));
            $post->setContent('Android Number ' . ($i + 1));
            $post->setAuthor($user);
            $manager->persist($post);
            $allPosts[] = $post;
        }

        $user = new User();
        $user->setUsername('6reputation');
        $password = $this->hasher->hashPassword($user, '6reputation');
        $user->setPassword($password);
        $manager->persist($user);
        
        for ($i = 0; $i < 6; $i++) {
            $post = new Post();
            $post->setTitle('6reputation ' . ($i + 1));
            $post->setContent('Android Number ' . ($i + 1));
            $post->setAuthor($user);
            $manager->persist($post);
            $allPosts[] = $post;
        }

        $user = new User();
        $user->setUsername('3reputation');
        $password = $this->hasher->hashPassword($user, '3reputation');
        $user->setPassword($password);
        $manager->persist($user);
        
        for ($i = 0; $i < 3; $i++) {
            $post = new Post();
            $post->setTitle('3reputation ' . ($i + 1));
            $post->setContent('Android Number ' . ($i + 1));
            $post->setAuthor($user);
            $manager->persist($post);
            $allPosts[] = $post;
        }

        $user = new User();
        $user->setUsername('0reputation');
        $password = $this->hasher->hashPassword($user, '0reputation');
        $user->setPassword($password);
        $manager->persist($user);

        $post = new Post();
        $post->setTitle('0reputation ' . ($i + 1));
        $post->setContent('Android Number ' . ($i + 1));
        $post->setAuthor($user);
        $manager->persist($post);
        $allPosts[] = $post;

        foreach ($allPosts as $post) {
            $vote = new PostVote();
            $vote->setPost($post);
            $vote->setAdmin($admin);
            $vote->setType('LIKE');
            $manager->persist($vote);
        }

        $manager->flush();
    }
}