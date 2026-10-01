<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// to check if the table exist run:
// docker compose exec -T database psql -U app -d app -c "\dt"

class ex01Controller extends AbstractController {

    #[Route(path:"/ex01", name:"ex01", methods:["GET", "POST", "DELETE"])]
    public function index(Request $request, EntityManagerInterface $manager): Response {
        $message = null;
        $status = null;

        $metadatas = $manager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($manager);
        if ($request->isMethod("POST")) {

            try {
                $schemaTool->updateSchema($metadatas);
                $status = "success";
                $message = "Success: Database table created/updated";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: " . $e->getMessage();
            }
        }
        else if ($request->isMethod("DELETE")) {
            try {
                $schemaTool->dropSchema($metadatas);
                $status = "success";
                $message = "Success: Database table deleted";
            } catch (Exception $e) {
                $status = "error";
                $message = "Error: " . $e->getMessage();
            }
        }
        

        return $this->render('doctrineORM/index.html.twig', [
            'status'=> $status,
            'message'=> $message,
        ]);
    }
}