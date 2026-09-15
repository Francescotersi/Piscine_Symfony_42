<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;

class ex13Controller extends AbstractController {

    #[Route(path:'/new', name:'ex13_newTable')]
    public function newTable(EntityManagerInterface $em): Response {
        try {
            $schemaTool = new SchemaTool($em);
            $metadata = [$em->getClassMetadata(Employee::class)];
            $schemaTool->updateSchema($metadata);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
            return new Response('Successfully created/updated Employee table.');
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage());
        }
    }

    #[Route(path:'/list', name:'ex13_listTable')]
    public function listTable(EmployeeRepository $employeeRepository): Response {
        $employees = $employeeRepository->findAll();
        return $this->render('ex13/list.html.twig', [
            'employees' => $employees
        ]);
    }

    #[Route(path:'/edit/employee/{id}', name:'ex13_editEmployee')]
    public function editTable(int $id, Request $request, EntityManagerInterface $em, EmployeeRepository $employeeRepository): Response {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            $this->addFlash('error', 'Dipendente non trovato!');
            return $this->redirectToRoute('ex13_listTable');
        }

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Dipendente aggiornato con successo!');
            return $this->redirectToRoute('ex13_listTable');
        }

        return $this->render('ex13/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifica Dipendente'
        ]);
    }

    #[Route(path:'/delete/employee/{id}', name:'ex13_deleteEmployee')]
    public function deleteEmployee(int $id, EntityManagerInterface $em, EmployeeRepository $employeeRepository): Response {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            $this->addFlash('error', 'Impossibile eliminare: Dipendente non trovato!');
        } else {
            $em->remove($employee);
            $em->flush();
            $this->addFlash('success', 'Dipendente eliminato con successo!');
        }

        return $this->redirectToRoute('ex13_listTable');
    }

    #[Route(path:'/create', name:'ex13_createEmployee')]
    public function createEmployee(Request $request, EntityManagerInterface $em): Response {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($employee);
            $em->flush();
            $this->addFlash('success', 'Nuovo dipendente creato con successo!');
            return $this->redirectToRoute('ex13_listTable');
        }

        return $this->render('ex13/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Crea nuovo Dipendente'
        ]);
    }

    #[Route(path:'/seed', name:'ex13_seedEmployee')]
    public function seedEmployee(): Response {
        return new Response('Seed non implementato.');
    }
}
