<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Ex03Controller extends AbstractController {

    #[Route('/ex03', name: 'ex03')]
    public function extensionAction() {
        return $this->render('ex03.html.twig');
    }
}