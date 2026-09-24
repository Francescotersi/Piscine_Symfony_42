<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController {
    #[Route(path:'/e01/login', name:'e01_login')]
    public function login(AuthenticationUtils $authUtils): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('e01_home');
        }

        return $this->render('e01/login/login.html.twig', [
            'last_username' => $authUtils->getLastUsername(),
            'error'         => $authUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path:'/e01/logout', name:'e01_logout')]
    public function logout(): void {
        throw new \LogicException('Symfony`s Firewall intercepted');
    }
}