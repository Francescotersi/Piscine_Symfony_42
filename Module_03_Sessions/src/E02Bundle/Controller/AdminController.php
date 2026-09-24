<?php

namespace App\E02Bundle\Controller;

use App\E02Bundle\Entity\Admin;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/e02/admin', name: 'e02_admin')]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $admins = $em->getRepository(Admin::class)->findAll();

        return $this->render('e02/admin/adminPanel.html.twig', [
            'users' => $users,
            'admins' => $admins,
        ]);
    }

    #[Route('/e02/admin/delete/user/{id}', name: 'e02_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete_user_' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $user = $em->getRepository(User::class)->find($id);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('e02_admin');
    }

    #[Route('/e02/admin/delete/admin/{id}', name: 'e02_delete_admin', methods: ['POST'])]
    public function deleteAdmin(int $id, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete_admin_' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $admin = $em->getRepository(Admin::class)->find($id);
        if ($admin) {
            $currentUser = $this->getUser();
            if ($currentUser instanceof Admin && $currentUser->getId() === $admin->getId()) {
                $this->addFlash('error', 'You cannot delete yourself!');
                return $this->redirectToRoute('e02_admin');
            }

            $em->remove($admin);
            $em->flush();
        }
        return $this->redirectToRoute('e02_admin');
    }
}