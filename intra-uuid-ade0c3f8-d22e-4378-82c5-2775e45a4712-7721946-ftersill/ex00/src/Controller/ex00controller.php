<?php

namespace App\Controller;

use App\Service\DatabaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// to check if the table exist run:
// docker compose exec -T database psql -U app -d app -c "\dt"

class ex00controller extends AbstractController {

    #[Route(path:"/ex00", name:"ex00", methods:["GET", "POST", "DELETE"])]
    public function index(Request $request, DatabaseHandler $databaseHandler): Response {
        $result = null;

        if ($request->isMethod('POST')) {
            $result = $databaseHandler->createTable();
        } elseif ($request->isMethod('DELETE')) {
            $result = $databaseHandler->dropTable();
        }

        return $this->render('database/index.html.twig', [
            'status' => $result['status'] ?? null,
            'message' => $result['message'] ?? null,
        ]);
    }
}