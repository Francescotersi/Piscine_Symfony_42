<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ex06Controller extends AbstractController
{
    public function __construct(private SqlDatabaseManager $dbManager)
    {
    }

    #[Route(path: "/ex06/new", name: "ex06_newTable")]
    public function newTable(): Response
    {
        try {
            $this->dbManager->createTable();
            $this->addFlash('success', 'Success: Table created');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: Table not created - ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex06_listTable');
    }

    #[Route(path: "/ex06/delete/table", name: "ex06_deleteTable")]
    public function deleteTable(): Response
    {
        try {
            $this->dbManager->dropTable();
            $this->addFlash('success', 'Success: Table deleted');
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: Table not deleted');
        }
        return $this->redirectToRoute('ex06_listTable');
    }

    #[Route(path: "/ex06/add", name: "ex06_addUser")]
    public function addUser(Request $request): Response
    {
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
                    ],
                ])
                ->add("birthdate", DateTimeType::class, [
                    "label" => "BirthDate",
                    'required' => false,
                ])
                ->add("address", TextType::class, [
                    "label" => "Address",
                    'required' => false,
                ])
                ->add("submit", SubmitType::class, ["label" => "Submit"])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $data = $form->getData();
                $birthdate = $data['birthdate']?->format('Y-m-d H:i:s');
                $data['birthdate'] = $birthdate;

                $this->dbManager->addUser($data);

                return $this->redirectToRoute('ex06_listTable');
            }
            return $this->render('database/addUser.html.twig', [
                'form' => $form->createView(),
                'user' => null,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: cant add user - ' . $e->getMessage());
            return $this->redirectToRoute('ex06_listTable');
        }
    }

    #[Route(path: "/ex06/list", name: "ex06_listTable")]
    public function listTable(): Response
    {
        try {
            $results = $this->dbManager->getAllUsers();

            return $this->render('database/listTable.html.twig', [
                'users' => $results,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: Cant list the table ----> ' . $e->getMessage());
            return $this->render('database/listTable.html.twig', [
                'users' => [],
            ]);
        }
    }

    #[Route(path: "/ex06/delete/{id}", name: "ex06_deleteUser")]
    public function deleteUser(string $id): Response
    {
        if (!ctype_digit($id)) {
            $this->addFlash('error', 'Error: invalid user ID');
            return $this->redirectToRoute('ex06_listTable');
        }

        $userId = (int) $id;
        $user = $this->dbManager->getUserById($userId);

        if (!$user) {
            $this->addFlash('error', 'Error: no user with this ID has been found ' . $userId);
            return $this->redirectToRoute('ex06_listTable');
        }

        $this->dbManager->deleteUserById($userId);
        $this->addFlash('success', 'User "' . $user['username'] . '" erased');

        return $this->redirectToRoute('ex06_listTable');
    }

    #[Route(path: "/ex06/update/{id}", name: "ex06_updateUser")]
    public function updateUser(string $id, Request $request): Response
    {
        try {
            if (!ctype_digit($id)) {
                $this->addFlash('error', 'Error: invalid user ID');
                return $this->redirectToRoute('ex06_listTable');
            }

            $userId = (int) $id;
            $user = $this->dbManager->getUserById($userId);

            if (!$user) {
                $this->addFlash('error', 'Error: no user with this ID has been found ' . $userId);
                return $this->redirectToRoute('ex06_listTable');
            }

            $birthdate = null;
            if (!empty($user['birthdate'])) {
                $birthdate = new \DateTime($user['birthdate']);
            }

            $form = $this->createFormBuilder([
                'username' => $user['username'],
                'name' => $user['name'],
                'email' => $user['email'],
                'enable' => (bool) $user['enable'],
                'birthdate' => $birthdate,
                'address' => $user['address'],
            ])
                ->add("username", TextType::class, ["label" => "Username"])
                ->add("name", TextType::class, ["label" => "Name"])
                ->add("email", EmailType::class, ["label" => "Email"])
                ->add("enable", ChoiceType::class, [
                    "label" => "Enable",
                    'choices' => [
                        'Yes' => true,
                        'No' => false,
                    ],
                ])
                ->add("birthdate", DateTimeType::class, [
                    "label" => "BirthDate",
                    'required' => false,
                ])
                ->add("address", TextType::class, [
                    "label" => "Address",
                    'required' => false,
                ])
                ->add("submit", SubmitType::class, ["label" => "Submit"])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $data = $form->getData();
                $birthdate = $data['birthdate']?->format('Y-m-d H:i:s');
                $data['birthdate'] = $birthdate;

                $this->dbManager->updateUser($userId, $data);

                $this->addFlash('success', 'User updated');
                return $this->redirectToRoute('ex06_listTable');
            }
            return $this->render('database/editUser.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
            ]);
        } catch (Exception $e) {
            $this->addFlash('error', 'Error: cant update user - ' . $e->getMessage());
            return $this->redirectToRoute('ex06_listTable');
        }
    }
}