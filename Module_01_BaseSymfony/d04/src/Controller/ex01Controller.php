<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ex01Controller {

    #[Route('/e01/firstpage', name:'ex01_firstpage')]
    public function index(): Response
    {
        return new Response(
            file_get_contents('./base.html.twig'),
        );
    }
}