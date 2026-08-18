<?php

namespace App\Controller;

use App\Entity\ex05;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ex05Controller extends AbstractController {

    #[Route(path:'/ex05/new', name:'ex05_newTable')]
    public function newTable(EntityManagerInterface $manager): Response {
        $schemaTool = new SchemaTool($manager);
        $metadata = [$manager->getClassMetadata(ex05::class)];
        try {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
            
            $this->addFlash('success', 'Table successfully created/reset!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating the table: ' . $e->getMessage());
        }

        return $this->redirectToRoute('listTable');
    }

    #[Route(path:'/ex05/add', name:'ex05_addUser')]
    public function addUser(): Response {

    }
}