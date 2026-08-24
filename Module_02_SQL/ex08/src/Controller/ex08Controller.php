<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\DBAL\Connection;


class ex08Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:"/", name:"homePage")]
    public function homePage(): Response {
        return $this->render('/base.html.twig');
    }

    #[Route(path:"/newTable", name:"newTable")]
    public function newTable(): Response {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS persons (
                id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                enable BOOLEAN NOT NULL,
                birthdate TIMESTAMP NULL
                );
            ";
            $this->connection->executeStatement($sql);

            $this->addFlash('success', 'La tabella persons è stata creata con successo!');
        } catch (Exception $e) {
            $this->addFlash('error', 'Errore durante la creazione: ' . $e->getMessage());
        }
        return $this->redirectToRoute('homePage');
    }

    #[Route(path:"/addColumn", name:"addColumn")]
    public function addColumn(): Response {
        try {
            $sql = "
                ALTER TABLE persons
                ADD COLUMN IF NOT EXISTS marital_status ENUM('single', 'married', 'widower');
            ";
            $this->connection->executeStatement($sql);
            $this->addFlash('success', 'Column successfully added!');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error accured while adding a column: ' . $e->getMessage());
        }

        return $this->redirectToRoute('homePage');
    }


}
