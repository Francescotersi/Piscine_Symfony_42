<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {
    #[Route(path:'/e01/homepage', name:'e01_home')]
    public function homepage(): Response {
        return $this->render('e01/home/index.html.twig');
    }
}