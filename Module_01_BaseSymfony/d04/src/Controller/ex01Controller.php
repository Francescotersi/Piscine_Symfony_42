<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ex01Controller extends AbstractController
{
    private const PAGES = ['wall-e', 'up', 'madagascar'];

    #[Route('/e01', name:'ex01_main')]
    public function index(): Response
    {
        return $this->render('ex01/index.html.twig', [
            'articles' => self::PAGES,
        ]);
    }

    #[Route('/e01/{article}', name:'ex01_article')]
    public function showArticle(string $article): Response
    {
        if (!in_array($article, self::PAGES)) {
            return $this->render("ex01/index.html.twig", [
                'articles' => self::PAGES,
                'error_message' => "The Chosen films hasn`t been made yet. Please choose one that exists:"
            ]);
        }
        else {
            return $this->render("ex01/{$article}.html.twig", [
                'title' =>ucfirst($article),
            ]);
        }
    }
}