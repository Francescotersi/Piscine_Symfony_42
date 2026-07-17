<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ex03Controller extends AbstractController {

    #[Route(path:"/ex02/new", name:"ex03_newTable")]
    public function newTable(): Response {
        $message = $this->newTable();
        return new Response($message);
    }

    public function deleteTable(): Response {
        $message = $this->deleteTable();
        return new Response($message);
    }
}