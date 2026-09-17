<?php

namespace App\Controller;

use App\Entity\userModel;
use App\Service\databaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


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
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, ['label' => 'Username'])
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('enable', ChoiceType::class, [
                'label' => 'Enable',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
            ])
            ->add('birthdate', DateTimeType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'input' => 'string',
                'html5' => true,
            ])
            ->add('address', TextType::class, ['label' => 'Address'])
            ->add('submit', SubmitType::class, [
                'label' => 'Save Note',
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $birth = $user->getBirthdate();
            if ($birth instanceof \DateTimeInterface) {
                $user->setBirthdate($birth->format('Y-m-d H:i:s'));
            }
            if ($dbHandler->newEntity($user)) {
                return $this->redirectToRoute('ex03_listTable');
            }
        }

        return $this->render('database/updateTable.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
