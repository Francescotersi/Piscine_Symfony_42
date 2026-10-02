<?php

namespace App\Controller;

use App\Service\databaseHandler;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ex01Controller extends AbstractController
{
    #[Route(path: "/ex01", name: "ex01", methods: ["GET", "POST", "DELETE"])]
    public function index(Request $request, databaseHandler $dbHandler): Response
    {
        $message = null;
        $status = null;

        if ($request->isMethod("POST")) {
            try {
                $dbHandler->createSchema();
                $status = "success";
                $message = "Success: Database table created/updated";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: " . $e->getMessage();
            }
        } elseif ($request->isMethod("DELETE")) {
            try {
                $dbHandler->dropSchema();
                $status = "success";
                $message = "Success: Database table deleted";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: " . $e->getMessage();
            }
        }

        return $this->render('doctrineORM/index.html.twig', [
            'status' => $status,
            'message' => $message,
        ]);
    }
}