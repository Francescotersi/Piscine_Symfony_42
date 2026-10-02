<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ex14Controller extends AbstractController
{
    public function __construct(private SqlDatabaseManager $dbManager)
    {
    }

    #[Route(path: '/new', name: 'ex14_newTable')]
    public function newTable(): Response
    {
        try {
            $this->dbManager->createTable();
            $this->addFlash('success', 'tables has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex14_listTable');
    }

    #[Route(path: '/drop', name: 'ex14_dropTable')]
    public function dropTable(): Response
    {
        try {
            $this->dbManager->dropTable();
            $this->addFlash('success', 'Table generic deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while deleting table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex14_listTable');
    }

    #[Route(path: '/seed/{number}', name: 'ex14_seedTable')]
    public function seedTable(string $number): Response
    {
        $newusers = (int) $number;
        if ($newusers <= 0) {
            $this->addFlash('error', 'The number must be greater than 0.');
            return $this->redirectToRoute('ex14_listTable');
        }

        try {
            $this->dbManager->seedUsers($newusers);
            $this->addFlash('success', "$newusers random users inserted successfully!");
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error during seed: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex14_listTable');
    }

    #[Route(path: '/list', name: 'ex14_listTable', methods: ['GET'])]
    public function listTable(): Response
    {
        $tableExists = true;
        $users = [];

        try {
            $users = $this->dbManager->getAllUsers();
        } catch (\Exception $e) {
            $tableExists = false;
        }
        return $this->render('listTable.html.twig', [
            'table_exists' => $tableExists,
            'users' => $users,
        ]);
    }

    #[Route(path: '/add', name: 'ex14_addUser', methods: ['GET', 'POST'])]
    public function addUser(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $username = $data['username'];
                $this->dbManager->insertUser($username);

                $this->addFlash('success', 'User "' . $username . '" successfully added!');
                return $this->redirectToRoute('ex14_listTable');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error while inserting user: ' . $e->getMessage());
            }
        }
        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}