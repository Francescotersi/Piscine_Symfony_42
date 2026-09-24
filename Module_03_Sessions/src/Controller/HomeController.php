<?php

namespace App\Controller;

use App\E03Bundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController {
    #[Route('/', name: 'home')]
    #[Route('/e01/homepage', name: 'e01_home')]
    #[Route('/e03/homepage', name: 'e03_home')]
    public function homepage(EntityManagerInterface $manager): Response {
        $posts = $manager->getRepository(Post::class)->findBy([], ['created' => 'DESC']);
        return $this->render('e01/home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}