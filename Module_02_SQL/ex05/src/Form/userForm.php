<?php

namespace App\Form;

use App\Entity\UserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class userForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', TextType::class, ['label' => 'Username'])
        ->add('name', TextType::class, ['label' => 'Name'])
        ->add('email', EmailType::class, ['label' => 'Email'])
        ->add('enable', ChoiceType::class, [
            'label'=> 'Enable',
            'choices' => [
                'Yes' => true,
                'No' => false,
            ]
        ])
        ->add('birthdate', DateTimeType::class, [
            'label' => 'Birthdate',
            'widget' => 'single_text',
            'input' => 'string',
            'html5' => true,
        ])
        ->add('address', TextType::class, ['label' => 'Address'])
        ->add('submit', SubmitType::class, [
            'label'=> 'Save Note'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class,
        ]);
    }
}