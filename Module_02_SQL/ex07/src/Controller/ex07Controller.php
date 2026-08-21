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
        $dbHandler->deleteEntity($id);

        return $this->redirectToRoute('ex07_listTable');
    }

    #[Route(path:"/ex07/add", name:"ex07_addUser")]
    public function updateTable(Request $request, databaseHandler $dbHandler): Response {
        $user = new userModel();
        $form = $this->createForm(userForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $birth = $user->getBirthdate();
            if ($birth instanceof \DateTimeInterface) {
                $user->setBirthdate($birth->format('Y-m-d H:i:s'));
            }
            if ($dbHandler->newEntity($user)) {
                return $this->redirectToRoute('ex05_listTable');
            }
        }

        return $this->render('database/updateTable.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path:"/ex07/edit/{id}", name:"ex07_editUser")]
    public function editUser(string $id, databaseHandler $dbHandler): Response {
        if (ctype_digit($id)) {

        }
        // https://codesamplez.com/development/php-doctrine-orm
    }
}
