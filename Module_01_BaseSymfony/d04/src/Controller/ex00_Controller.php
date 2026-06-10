<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ex00_Controller {

    // #[Route('/e00/firstpage', name:'ex00_firstpage')]   metodo moderno per definire una route
    /**
     * @Route("/e00/firstpage", name="ex00Firstpage")
     */
    public function index(): Response
    {
        return new Response(
            '<html><body>Hello World!</body></html>
        ');
    }
}