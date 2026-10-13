<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use JsonResponse;

class UserController extends AbstractController {

    #[Route('/login', name: 'login')]
    function loginAction() {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('login.html.twig');
    }

    #[Route(path:'/logout', name:'logout')]
    public function logout(): void {
        throw new \LogicException('Symfony`s Firewall intercepted');
    }
}