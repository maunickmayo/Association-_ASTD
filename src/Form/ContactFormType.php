<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => 
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]), 
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'choices' => [
                    'Demande' => 'Demande',
                    'Question' => 'Question',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message', 
                    'constraints' => [
                        new Regex([
                            'pattern' => '/[A-Za-z]+$/i',
                            'match' => true,
                            'message' =>'Veuillez entrer uniquement des lettres',
                        ]),
                        new NotBlank([
                            'message' => "Ce champ ne peut être vide"
                        ]), 
                        new Length([
                            'min' => 10,
                            'minMessage' => 'Message trop court, veuillez entrer au minimum 10 caractères.
                            ' ]), 
                    ],     
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
