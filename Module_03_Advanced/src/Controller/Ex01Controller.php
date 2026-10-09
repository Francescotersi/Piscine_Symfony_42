<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Ex01Controller extends AbstractController {

    #[Route('/ex01', name: 'ex01')]
    public function ex01Action(): Response {
        $number = $this->getParameter('d07.number');
        $enable = $this->getParameter('d07.enable');
        return new Response("Number: " . (string) $number . " Enable: " . (string) $enable);
    }
}