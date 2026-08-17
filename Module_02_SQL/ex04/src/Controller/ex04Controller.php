<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\DBAL\Connection;
use Exception;

class ex04Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:"/ex04/new", name:"ex04_newTable")]
    public function newTable(): Response {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL
            );
        ";
        $this->connection->executeStatement($sql);

        return new Response("Success: Table created");
    }

    #[Route(path:"/ex04/delete/table", name:"ex04_deleteTable")]
    public function deleteTable(): Response {
        try {
        $sql = "DROP TABLE IF EXISTS users";
        
        $this->connection->executeStatement($sql);
        
        return new Response("Success: Table deleted");
        }   catch (Exception $e) {
            return new Response("Error: Table not deleted");
        }
    }

    #[Route(path:"/ex04/add", name:"ex04_addUser")]
    public function addUser(Request $request): Response {
        try {
            $form = $this->createFormBuilder()
                ->add("username", TextType::class, ["label"=> "Username"])
                ->add("submit", SubmitType::class, ["label"=> "Submit"])
                ->getForm();

            $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $sql = "INSERT INTO users (username)
                        VALUES (:username)
                        ON CONFLICT DO NOTHING";

                $this->connection->executeStatement($sql, ['username' => $data['username']]);

                return $this->redirectToRoute('ex04_listTable');
        }
        return $this->render('database/updateTable.html.twig', [
            'form' => $form->createView(),
        ]);
        } catch (Exception $e) {
            return new Response('Error: cant update table');
        }
    }

    #[Route(path:"/ex04/list", name:"ex04_listTable")]
    public function listTable(): Response {
        try {
        $sql = "SELECT * FROM users";
        $results = $this->connection->fetchAllAssociative($sql);

        return $this->render('database/listTable.html.twig', [
            'users' => $results,
        ]);
        } catch (Exception $e) {
            return new Response("Error: Cant list the table");
        }
    }

    #[Route(path:"/ex04/delete/{id}", name:"ex04_deleteUser")]
    public function deleteUser(string $id): Response {
        if (!ctype_digit($id)) {
            return new Response('Error: invalid user ID', Response::HTTP_NOT_FOUND);
        }

        $userId = (int) $id;
        $sqlSelect = 'SELECT * FROM users WHERE id = :id';
        $user = $this->connection->fetchAssociative($sqlSelect, ['id' => $userId]);

        if (!$user) {
            return new Response('Error: no user with this ID has been found ' . $userId, Response::HTTP_NOT_FOUND);
        }

        $sqlDelete = 'DELETE FROM users WHERE id = :id';
        $this->connection->executeStatement($sqlDelete, ['id' => $userId]);

        $this->addFlash('success', 'User "' . $user['username'] . '" erased');

        return $this->redirectToRoute('ex04_listTable');
    }

}