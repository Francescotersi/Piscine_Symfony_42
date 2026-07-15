<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\DBAL\Connection;

// to check if the table exist run:
// docker compose exec -T database psql -U app -d app -c "\dt"

class ex02Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:"/ex02/new", name:"ex02_newTable")]
    public function tableSetUp(): Response {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            enable BOOLEAN NOT NULL,
            birthdate TIMESTAMP NULL,
            address TEXT
            );
        ";
        $this->connection->executeStatement($sql);

        return new Response("Success: Table created");
    }

    #[Route(path:"/ex02/update", name:"ex02_updateTable")]
    public function tableUpdate(Request $request): Response {
        $form = $this->createFormBuilder()
            ->add("username", TextType::class, ["label"=> "Username"])
            ->add("name", TextType::class, ["label"=> "Name"])
            ->add("email", EmailType::class, ["label"=> "Email"])
            ->add("enable", ChoiceType::class, ["label"=> "Enable", 
                                        'choices' => [
                                            'Yes' => true,
                                            'No' => false,
                                        ]])
            ->add("birthdate", DateType::class, ["label"=> "BirthDate"])
            ->add("address", TextType::class, ["label"=> "Address"])
            ->add("submit", SubmitType::class, ["label"=> "Submit!"])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $birthdate = $data['birthdate'];
            if ($birthdate instanceof \DateTimeInterface) {
                $birthdate = $birthdate->format('Y-m-d');
            }

            $sql = "INSERT INTO users (username, name, email, enable, birthdate, address)
                    VALUES (:username, :name, :email, :enable, :birthdate, :address)
                    ON CONFLICT DO NOTHING";

            $this->connection->executeStatement($sql, [
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'enable' => $data['enable'],
                'birthdate' => $birthdate,
                'address' => $data['address'],
            ]);

            return $this->redirectToRoute('ex02_listTable');
        }
        return $this->render('database/updateTable.html.twig', [
            'form' => $form->createView(),
        ]); 
    }

    #[Route(path:"/ex02/list", name:"ex02_listTable")]
    public function listTable(): Response {
        $sql = "SELECT * FROM users";
        $results = $this->connection->fetchAllAssociative($sql);

        return $this->render('database/listTable.html.twig', [
            'users' => $results,
        ]);
    }

    #[Route(path:'/ex02/delete', name:'ex02_deleteTable')]
    public function deleteTable(): Response {
        $sql = 'DROP TABLE IF EXISTS users';
        
        $this->connection->executeStatement($sql);

        return new Response("Success: Table deleted");
    }
}
