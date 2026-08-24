<?php

namespace App\Controller;

use App\Entity\userModel;
use App\Form\userForm;
use App\Service\databaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ex07Controller extends AbstractController {

    #[Route(path:"/ex07/new", name:"ex07_newTable")]
    public function newTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->newTable();
        return new Response($message);
    }

    #[Route(path:"/ex07/delete", name:"ex07_deleteTable")]
    public function deleteTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->deleteTable();
        return new Response($message);
    }

    #[Route(path:"/ex07/list", name:"ex07_listTable")]
    public function listTable(databaseHandler $dbHandler): Response {
        $users = $dbHandler->fetchAll(userModel::class);

        return $this->render('database/listTable.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path:"/ex07/delete/{id}", name:"ex07_deleteUser", methods:["POST"])]
    public function deleteUser(int $id, databaseHandler $dbHandler): Response {
        if ($dbHandler->deleteEntity($id)) {
            $this->addFlash('success', 'User deleted successfully.');
        } else {
            $this->addFlash('error', 'User not found or could not be deleted.');
        }

        return $this->redirectToRoute('ex07_listTable');
    }

    #[Route(path:"/ex07/add", name:"ex07_addUser")]
    public function updateTable(Request $request, databaseHandler $dbHandler, ?int $id = null): Response {
        if ($id === null) {
            $user = new userModel();
        } else {
            $user = $dbHandler->getByID($id);
        }
        if ($user === null) {
            return new Response("Error: user not found in database");
        }
        $form = $this->createForm(userForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($dbHandler->newEntity($user)) {
                $this->addFlash(
                    'success',
                    $id === null ? 'User created successfully.' : 'User updated successfully.'
                );
                return $this->redirectToRoute('ex07_listTable');
            }
            $this->addFlash('error', 'Could not save the user.');
        }

        return $this->render(
            $id === null
                ? 'database/updateTable.html.twig'
                : 'database/editUser.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path:"/ex07/edit/{id}", name:"ex07_editUser", methods: ["GET", "POST"])]
    public function editUser(int $id, Request $request, databaseHandler $dbHandler): Response {
        return $this->updateTable($request, $dbHandler, $id);
    }
}
