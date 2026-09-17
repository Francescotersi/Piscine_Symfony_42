<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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

    private function buildEmployeeForm(Employee $employee): \Symfony\Component\Form\FormInterface
    {
        return $this->createFormBuilder($employee)
            ->add('firstname', TextType::class, ['required' => true])
            ->add('lastname', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
            ])
            ->add('employed_since', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('employed_until', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('hours', ChoiceType::class, [
                'choices' => [
                    '8' => 8,
                    '6' => 6,
                    '4' => 4,
                ],
                'required' => true,
            ])
            ->add('salary', IntegerType::class, ['required' => true])
            ->add('position', ChoiceType::class, [
                'choices' => [
                    'Manager' => 'manager',
                    'Account Manager' => 'account_manager',
                    'QA Manager' => 'qa_manager',
                    'Dev Manager' => 'dev_manager',
                    'CEO' => 'ceo',
                    'COO' => 'coo',
                    'Backend Dev' => 'backend_dev',
                    'Frontend Dev' => 'frontend_dev',
                    'QA Tester' => 'qa_tester',
                ],
                'required' => true,
            ])
            ->add('manager', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'email',
                'required' => false,
                'placeholder' => 'No Manager',
                'query_builder' => function (EmployeeRepository $er) use ($employee) {
                    $qb = $er->createQueryBuilder('e');
                    $qb->where($qb->expr()->in('e.position', ['manager', 'account_manager', 'qa_manager', 'dev_manager', 'ceo', 'coo']));
                    if ($employee && $employee->getId()) {
                        $qb->andWhere('e.id != :myId')
                           ->setParameter('myId', $employee->getId());
                    }
                    return $qb;
                },
            ])
            ->getForm();
    }

    #[Route(path:'/edit/employee/{id}', name:'ex13_editEmployee')]
    public function editTable(int $id, Request $request, EntityManagerInterface $em, EmployeeRepository $employeeRepository): Response {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            $this->addFlash('error', 'Employee not found!');
            return $this->redirectToRoute('ex13_listTable');
        }

        $form = $this->buildEmployeeForm($employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Employee updated successfully!');
            return $this->redirectToRoute('ex13_listTable');
        }

        return $this->render('ex13/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Employee'
        ]);
    }

    #[Route(path:'/delete/employee/{id}', name:'ex13_deleteEmployee')]
    public function deleteEmployee(int $id, EntityManagerInterface $em, EmployeeRepository $employeeRepository): Response {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            $this->addFlash('error', 'Cannot delete: Employee not found!');
        } else {
            $em->remove($employee);
            $em->flush();
            $this->addFlash('success', 'Employee deleted successfully!');
        }

        return $this->redirectToRoute('ex13_listTable');
    }

    #[Route(path:'/create', name:'ex13_createEmployee')]
    public function createEmployee(Request $request, EntityManagerInterface $em): Response {
        $employee = new Employee();
        $form = $this->buildEmployeeForm($employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($employee);
            $em->flush();
            $this->addFlash('success', 'New employee created successfully!');
            return $this->redirectToRoute('ex13_listTable');
        }

        return $this->render('ex13/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create new Employee'
        ]);
    }

    #[Route(path:'/seed', name:'ex13_seedEmployee')]
    public function seedEmployee(): Response {
        return new Response('Seed not implemented.');
    }
}
