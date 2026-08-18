<?php

namespace App\Controller;

use App\Entity\userModel;
use App\Form\userForm;
use App\Service\databaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ex05Controller extends AbstractController {

    #[Route(path:"/ex05/new", name:"ex05_newTable")]
    public function newTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->newTable();
        return new Response($message);
    }

    #[Route(path:"/ex05/delete", name:"ex05_deleteTable")]
    public function deleteTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->deleteTable();
        return new Response($message);
    }

    #[Route(path:"/ex05/list", name:"ex05_listTable")]
    public function listTable(databaseHandler $dbHandler): Response {
        $users = $dbHandler->fetchAll(userModel::class);

        return $this->render('database/listTable.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path:"/ex05/delete/{id}", name:"ex05_deleteUser", methods:["POST"])]
    public function deleteUser(int $id, databaseHandler $dbHandler): Response {
        $dbHandler->deleteEntity($id);

        return $this->redirectToRoute('ex05_listTable');
    }

    #[Route(path:"/ex05/add", name:"ex05_updateTable")]
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
}
