<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ex08Controller extends AbstractController
{
    public function __construct(private SqlDatabaseManager $dbManager)
    {
    }

    #[Route(path: "/", name: "homePage")]
    public function homePage(): Response
    {
        return $this->render('/base.html.twig');
    }

    #[Route(path: "/newTable", name: "newTable")]
    public function newTable(): Response
    {
        try {
            $this->dbManager->createPersonsTable();
            $this->addFlash('success', 'Persons table has been created');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error while creatng Persons table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('homePage');
    }

    #[Route(path: "/addColumn", name: "addColumn")]
    public function addColumn(): Response
    {
        try {
            $this->dbManager->addMaritalStatusColumn();
            $this->addFlash('success', 'Column successfully added!');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error accured while adding a column: ' . $e->getMessage());
        }

        return $this->redirectToRoute('homePage');
    }

    #[Route(path: "/otherTables", name: "otherTables")]
    public function otherTables(): Response
    {
        try {
            $this->dbManager->createRelatedTables();
            $this->addFlash('success', 'Addresses and bank_accounts tables have been created');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error while creatng Addresses and Bank_accounts table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('homePage');
    }
}
