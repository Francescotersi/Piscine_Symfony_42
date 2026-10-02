<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ex00controller extends AbstractController
{
    #[Route(path: "/ex00", name: "ex00", methods: ["GET", "POST", "DELETE"])]
    public function index(Request $request, SqlDatabaseManager $dbManager): Response
    {
        $message = null;
        $status = null;

        if ($request->isMethod('POST')) {
            try {
                $dbManager->createTable();
                $status = "success";
                $message = "Success: Table created/updated";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: Table not created/updated" . $e->getMessage();
            }
        } elseif ($request->isMethod('DELETE')) {
            try {
                $dbManager->dropTable();
                $status = "success";
                $message = "Success: Table deleted";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: Table not deleted" . $e->getMessage();
            }
        }

        return $this->render('database/index.html.twig', [
            'status' => $status,
            'message' => $message,
        ]);
    }
}