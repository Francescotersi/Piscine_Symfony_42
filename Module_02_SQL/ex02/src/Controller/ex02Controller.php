<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ex02Controller extends AbstractController
{
    public function __construct(private SqlDatabaseManager $dbManager)
    {
    }

    #[Route(path: "/ex02/new", name: "ex02_newTable")]
    public function tableSetUp(): Response {
        $this->dbManager->createTable();
        $this->addFlash('success', 'Success: Table created');
        return $this->redirectToRoute('ex02_listTable');
    }

    #[Route(path: "/ex02/update", name: "ex02_updateTable")]
    public function tableUpdate(Request $request): Response {
        try {
            $form = $this->createFormBuilder()
                ->add("username", TextType::class, ["label" => "Username"])
                ->add("name", TextType::class, ["label" => "Name"])
                ->add("email", EmailType::class, ["label" => "Email"])
                ->add("enable", ChoiceType::class, [
                    "label" => "Enable",
                    'choices' => [
                        'Yes' => true,
                        'No' => false,
                    ]
                ])
                ->add("birthdate", DateTimeType::class, ["label" => "BirthDate"])
                ->add("address", TextType::class, ["label" => "Address"])
                ->add("submit", SubmitType::class, ["label" => "Submit!"])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $birthdate = $data['birthdate'];
                if ($birthdate instanceof \DateTimeInterface) {
                    $birthdate = $birthdate->format('Y-m-d H:i:s');
                }
                $data['birthdate'] = $birthdate;

                $this->dbManager->insertUser($data);

                return $this->redirectToRoute('ex02_listTable');
            }
            return $this->render('database/updateTable.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: cant update table');
            return $this->redirectToRoute('ex02_listTable');
        }
    }

    #[Route(path: "/ex02/list", name: "ex02_listTable")]
    public function listTable(): Response {
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

    #[Route(path: '/ex02/delete', name: 'ex02_deleteTable')]
    public function deleteTable(): Response
    {
        try {
            $this->dbManager->dropTable();
            $this->addFlash('success', 'Success: Table deleted');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: Table not deleted');
        }
        return $this->redirectToRoute('ex02_listTable');
    }
}
