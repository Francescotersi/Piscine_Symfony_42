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

            $this->addFlash('success', 'Persons table has been created');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error while creatng Persons table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('homePage');
    }

    #[Route(path:"/addColumn", name:"addColumn")]
    public function addColumn(): Response {
        try {
            $sql = "
                ALTER TABLE persons
                ADD COLUMN IF NOT EXISTS marital_status VARCHAR(20) 
                CHECK (marital_status IN ('single', 'married', 'widower')
                );
            ";
            $this->connection->executeStatement($sql);
            $this->addFlash('success', 'Column successfully added!');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error accured while adding a column: ' . $e->getMessage());
        }

        return $this->redirectToRoute('homePage');
    }

    #[Route(path:"/otherTables", name:"otherTables")]
    public function otherTables(): Response {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS bank_accounts (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                money INTEGER NOT NULL,
                owner_id INTEGER NOT NULL UNIQUE,
                FOREIGN KEY (owner_id) REFERENCES persons(id)
                ON DELETE CASCADE
                );

                CREATE TABLE IF NOT EXISTS addresses (
                id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                road VARCHAR(255) NOT NULL,
                owner_id INT,
                FOREIGN KEY (owner_id) REFERENCES persons(id)
                );
            ";
            $this->connection->executeStatement($sql);

            $this->addFlash('success', 'Addresses and bank_accounts tables have been created');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error while creatng Addresses and Bank_accounts table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('homePage');
    }
    
}
