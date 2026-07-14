<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// to check if the table exist run:
// docker compose exec -T database psql -U app -d app -c "\dt"

class ex00controller extends AbstractController {

    #[Route(path:"/ex00", name:"ex00", methods:["GET", "POST", "DELETE"])]
    public function index(Request $request, Connection $connection): Response {
        $message = null;
        $status = null;

        if ($request->isMethod('POST')) {
            $sql = "
                CREATE TABLE IF NOT EXISTS users_data (
                id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) UNIQUE NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                enable BOOLEAN NOT NULL,
                birthdate TIMESTAMP NULL,
                address TEXT
                );
            ";
            try {
                $connection->executeStatement($sql);
                $status = "success";
                $message = "Success: Table created/updated";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: Table not created/updated" . $e->getMessage();
            }
        }
        else if ($request->isMethod('DELETE')) {
            try {
                $connection->executeStatement('DROP TABLE IF EXISTS users_data');
                $status = "success";
                $message = "Success: Table deleted";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: Table not deleted" . $e->getMessage();
            }
        }

        return $this->render('database/index.html.twig', [
            'status'=> $status,
            'message'=> $message,
        ]);
    }
}