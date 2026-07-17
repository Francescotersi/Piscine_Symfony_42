<?php

namespace App\Controller;

use App\Entity\userModel;
use App\Form\userForm;
use App\Service\databaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ex03Controller extends AbstractController {

    #[Route(path:"/ex03/new", name:"ex03_newTable")]
    public function newTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->newTable();
        return new Response($message);
    }

    #[Route(path:"/ex03/delete", name:"ex03_deleteTable")]
    public function deleteTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->deleteTable();
        return new Response($message);
    }

    #[Route(path:"/ex03/list", name:"ex03_listTable")]
    public function listTable(databaseHandler $dbHandler): Response {
        $users = $dbHandler->fetchAll(userModel::class);

        return $this->render('database/listTable.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path:"/ex03/update", name:"ex03_updateTable")]
    public function updateTable(Request $request, databaseHandler $dbHandler): Response {
        $user = new userModel();
        $form = $this->createForm(userForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $birth = $user->getBirthdate();
            if ($birth instanceof \DateTimeInterface) {
                $user->setBirthdate($birth->format('Y-m-d H:i:s'));
            }
            $dbHandler->newEntity($user);
            return $this->redirectToRoute('ex03_listTable');
        }

        return $this->render('database/updateTable.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
