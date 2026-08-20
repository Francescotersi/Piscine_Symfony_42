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

class ex06Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:"/ex06/new", name:"ex06_newTable")]
    public function newTable(): Response {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS users_data (
                id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                enable BOOLEAN NOT NULL,
                birthdate TIMESTAMP NULL,
                address TEXT
                );
            ";
            $this->connection->executeStatement($sql);

            return new Response("Success: Table created");
        } catch (Exception $e) {
            return new Response("Error: Table not created - " . $e->getMessage());
        }
    }

    #[Route(path:"/ex06/delete/table", name:"ex06_deleteTable")]
    public function deleteTable(): Response {
        try {
        $sql = "DROP TABLE IF EXISTS users_data";
        
        $this->connection->executeStatement($sql);
        
        return new Response("Success: Table deleted");
        }   catch (Exception $e) {
            return new Response("Error: Table not deleted");
        }
    }

    #[Route(path:"/ex06/add", name:"ex06_addUser")]
    public function addUser(Request $request): Response {
        try {
            $form = $this->createFormBuilder()
                ->add("username", TextType::class, ["label"=> "Username"])
                ->add("name", TextType::class, ["label"=> "Name"])
                ->add("email", EmailType::class, ["label"=> "Email"])
                ->add("enable", ChoiceType::class, [
                    "label"=> "Enable",
                    'choices' => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ])
                ->add("birthdate", DateTimeType::class, [
                    "label"=> "BirthDate",
                    'required' => false,
                ])
                ->add("address", TextType::class, [
                    "label"=> "Address",
                    'required' => false,
                ])
                ->add("submit", SubmitType::class, ["label"=> "Submit"])
                ->getForm();

            $form->handleRequest($request);
             if ($form->isSubmitted()) {
                $data = $form->getData();
                $birthdate = $data['birthdate']?->format('Y-m-d H:i:s');

                $sql = "INSERT INTO users_data (username, name, email, enable, birthdate, address)
                        VALUES (:username, :name, :email, :enable, :birthdate, :address)";

                $this->connection->executeStatement($sql, [
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'enable' => $data['enable'],
                    'birthdate' => $birthdate,
                    'address' => $data['address'],
                ]);

                return $this->redirectToRoute('ex06_listTable');
        }
        return $this->render('database/addUser.html.twig', [
            'form' => $form->createView(),
            'user' => null,
        ]);
        } catch (Exception $e) {
            return new Response('Error: cant add user - ' . $e->getMessage());
        }
    }

    #[Route(path:"/ex06/list", name:"ex06_listTable")]
    public function listTable(): Response {
        try {
        $sql = "SELECT * FROM users_data";
        $results = $this->connection->fetchAllAssociative($sql);

        return $this->render('database/listTable.html.twig', [
            'users' => $results,
        ]);
        } catch (Exception $e) {
            return new Response("Error: Cant list the table ----> " . $e->getMessage());
        }
    }

    #[Route(path:"/ex06/delete/{id}", name:"ex06_deleteUser")]
    public function deleteUser(string $id): Response {
        if (!ctype_digit($id)) {
            return new Response('Error: invalid user ID', Response::HTTP_NOT_FOUND);
        }

        $userId = (int) $id;
        $sqlSelect = 'SELECT * FROM users_data WHERE id = :id';
        $user = $this->connection->fetchAssociative($sqlSelect, ['id' => $userId]);

        if (!$user) {
            return new Response('Error: no user with this ID has been found ' . $userId, Response::HTTP_NOT_FOUND);
        }

        $sqlDelete = 'DELETE FROM users_data WHERE id = :id';
        $this->connection->executeStatement($sqlDelete, ['id' => $userId]);

        $this->addFlash('success', 'User "' . $user['username'] . '" erased');

        return $this->redirectToRoute('ex06_listTable');
    }

    #[Route(path:"/ex06/update/{id}", name:"ex06_updateUser")]
    public function updateUser(string $id, Request $request): Response {
        try {
            if (!ctype_digit($id)) {
                return new Response('Error: invalid user ID');
            }

            $userId = (int) $id;
            $sqlSelect = 'SELECT * FROM users_data WHERE id = :id';
            $user = $this->connection->fetchAssociative($sqlSelect, ['id' => $userId]);

            if (!$user) {
                return new Response('Error: no user with this ID has been found ' . $userId, Response::HTTP_NOT_FOUND);
            }

            $birthdate = null;
            if (!empty($user['birthdate'])) {
                $birthdate = new \DateTime($user['birthdate']);
            }

            $form = $this->createFormBuilder([
                'username' => $user['username'],
                'name' => $user['name'],
                'email' => $user['email'],
                'enable' => (bool) $user['enable'],
                'birthdate' => $birthdate,
                'address' => $user['address'],
            ])
                ->add("username", TextType::class, ["label"=> "Username"])
                ->add("name", TextType::class, ["label"=> "Name"])
                ->add("email", EmailType::class, ["label"=> "Email"])
                ->add("enable", ChoiceType::class, [
                    "label"=> "Enable",
                    'choices' => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ])
                ->add("birthdate", DateTimeType::class, [
                    "label"=> "BirthDate",
                    'required' => false,
                ])
                ->add("address", TextType::class, [
                    "label"=> "Address",
                    'required' => false,
                ])
                ->add("submit", SubmitType::class, ["label"=> "Submit"])
                ->getForm();

            $form->handleRequest($request);
             if ($form->isSubmitted()) {
                $data = $form->getData();
                $birthdate = $data['birthdate']?->format('Y-m-d H:i:s');

                $sql = "UPDATE users_data
                        SET username = :username,
                            name = :name,
                            email = :email,
                            enable = :enable,
                            birthdate = :birthdate,
                            address = :address
                        WHERE id = :id";

                $this->connection->executeStatement($sql, [
                    'id' => $userId,
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'enable' => $data['enable'],
                    'birthdate' => $birthdate,
                    'address' => $data['address'],
                ]);

                $this->addFlash('success', 'User updated');

                return $this->redirectToRoute('ex06_listTable');
        }
        return $this->render('database/editUser.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
        } catch (Exception $e) {
            return new Response('Error: cant update user - ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}