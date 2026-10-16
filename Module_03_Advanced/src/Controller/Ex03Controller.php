<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Ex03Controller extends AbstractController {

    #[Route('/{_locale}/ex03', name: 'ex03',requirements: ['_locale' => 'en|fr'])]
    public function extensionAction() {
        return $this->render('ex03.html.twig');
    }
}