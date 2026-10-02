<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Exception;

class ex04Controller extends AbstractController
{
    public function __construct(private SqlDatabaseManager $dbManager)
    {
    }

    #[Route(path: "/ex04/new", name: "ex04_newTable")]
    public function newTable(): Response
    {
        $this->dbManager->createTable();
        $this->addFlash('success', 'Success: Table created');
        return $this->redirectToRoute('ex04_listTable');
    }

    #[Route(path: "/ex04/delete/table", name: "ex04_deleteTable")]
    public function deleteTable(): Response
    {
        try {
            $this->dbManager->dropTable();
            $this->addFlash('success', 'Success: Table deleted');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: Table not deleted');
        }
        return $this->redirectToRoute('ex04_listTable');
    }

    #[Route(path: "/ex04/add", name: "ex04_addUser")]
    public function addUser(Request $request): Response
    {
        try {
            $form = $this->createFormBuilder()
                ->add("username", TextType::class, ["label" => "Username"])
                ->add("submit", SubmitType::class, ["label" => "Submit"])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $this->dbManager->addUser($data['username']);
                return $this->redirectToRoute('ex04_listTable');
            }
            return $this->render('database/updateTable.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: cant update table');
            return $this->redirectToRoute('ex04_listTable');
        }
    }

    #[Route(path: "/ex04/list", name: "ex04_listTable")]
    public function listTable(): Response
    {
        try {
            $results = $this->dbManager->getAllUsers();
            return $this->render('database/listTable.html.twig', [
                'users' => $results,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: Cant list the table');
            return $this->render('database/listTable.html.twig', [
                'users' => [],
            ]);
        }
    }

    #[Route(path: "/ex04/delete/{id}", name: "ex04_deleteUser")]
    public function deleteUser(string $id): Response
    {
        if (!ctype_digit($id)) {
            $this->addFlash('error', 'Error: invalid user ID');
            return $this->redirectToRoute('ex04_listTable');
        }

        $userId = (int) $id;
        $user = $this->dbManager->getUserById($userId);

        if (!$user) {
            $this->addFlash('error', 'Error: no user with this ID has been found ' . $userId);
            return $this->redirectToRoute('ex04_listTable');
        }

        $this->dbManager->deleteUserById($userId);
        $this->addFlash('success', 'User "' . $user['username'] . '" erased');

        return $this->redirectToRoute('ex04_listTable');
    }
}