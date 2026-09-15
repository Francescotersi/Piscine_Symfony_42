<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'query_builder' => function (\App\Repository\EmployeeRepository $er) use ($builder) {
                    $qb = $er->createQueryBuilder('e');
                    $qb->where($qb->expr()->in('e.position', ['manager', 'account_manager', 'qa_manager', 'dev_manager', 'ceo', 'coo']));
                    $employee = $builder->getData();
                    if ($employee && $employee->getId()) {
                        $qb->andWhere('e.id != :myId')
                           ->setParameter('myId', $employee->getId());
                    }
                    
                    return $qb;
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
