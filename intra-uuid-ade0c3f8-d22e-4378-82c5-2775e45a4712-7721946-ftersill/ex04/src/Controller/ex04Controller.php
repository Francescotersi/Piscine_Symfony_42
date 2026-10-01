<?php

namespace App\Controller;

use App\Service\DatabaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Exception;

class ex04Controller extends AbstractController {

    public function __construct(private DatabaseHandler $databaseHandler) {}

    #[Route(path:"/ex04/new", name:"ex04_newTable")]
    public function newTable(): Response {
        $this->addFlash(
            $this->databaseHandler->createTable() ? 'success' : 'error',
            'Table creation completed'
        );
        return $this->redirectToRoute('ex04_listTable');
    }

    #[Route(path:"/ex04/delete/table", name:"ex04_deleteTable")]
    public function deleteTable(): Response {
        $this->addFlash(
            $this->databaseHandler->deleteTable() ? 'success' : 'error',
            'Table deletion completed'
        );
        return $this->redirectToRoute('ex04_listTable');
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

                if (!$this->databaseHandler->addUser($data['username'])) {
                    $this->addFlash('error', 'Error: user was not added');
                }

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

    #[Route(path:"/ex04/list", name:"ex04_listTable")]
    public function listTable(): Response {
        try {
        $results = $this->databaseHandler->listUsers();

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

    #[Route(path:"/ex04/delete/{id}", name:"ex04_deleteUser", methods:["POST"])]
    public function deleteUser(string $id): Response {
        if (!ctype_digit($id)) {
            $this->addFlash('error', 'Error: invalid user ID');
            return $this->redirectToRoute('ex04_listTable');
        }

        $userId = (int) $id;
        $user = $this->databaseHandler->getUserById($userId);

        if (!$user) {
            $this->addFlash('error', 'Error: no user with this ID has been found ' . $userId);
            return $this->redirectToRoute('ex04_listTable');
        }

        $user = $this->databaseHandler->deleteUser($userId);

        if (!$user) {
            $this->addFlash('error', 'Error: user could not be deleted');
            return $this->redirectToRoute('ex04_listTable');
        }

        $this->addFlash('success', 'User "' . $user['username'] . '" erased');

        return $this->redirectToRoute('ex04_listTable');
    }

}