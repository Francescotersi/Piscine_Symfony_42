<?php

namespace App\E02Bundle\Controller;

use App\E02Bundle\Entity\Admin;
use App\E02Bundle\Form\AdminRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminRegistrationController extends AbstractController
{
    #[Route('/e02/admin/register', name: 'e02_admin_register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminRegistrationFormType::class, $admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $admin->setPassword($hasher->hashPassword($admin, $plainPassword));
            // $admin->setRoles(['ROLE_ADMIN']);

            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('e01_login');
        }
        return $this->render('e02/registration/adminRegistration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}