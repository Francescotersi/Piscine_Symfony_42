<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Ex02Controller extends AbstractController {

    #[Route('/{_locale}/ex02/{count}', name: 'ex02',
        requirements: ['_locale' => 'en|fr', 'count' => '[0-9]'], 
        defaults: ['count' => 0])]
    public function translationsAction(int $count) {
        $number = $this->getParameter('d07.number');

        return $this->render('ex02.html.twig', [
            'number' => $number,
            'count' => $count
        ]);
    }
}