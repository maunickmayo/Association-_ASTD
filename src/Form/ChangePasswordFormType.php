<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           // ->add('password', PasswordType::class, [
                //'label' => 'Votre mot de passe actuel'
           // ])
           ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Votre mot de passe',
                'attr' => [
                   // 'autocomplete' => 'nouveau mot de passe',
                    'placeholder' => 'Mot de passe',
                    ],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(?=.*[a-z])(?=.*[A-Z)(?=*[0-9]).{6,}$/",
                        'match' => true,
                        'message' =>'Renseignez au moins 1 majuscule, 1 miniscule et 1 chiffre',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ],
            'second_options' => [
                'label' => 'Confirmer votre mot de passe',
                'attr' => [
                   // 'autocomplete' => 'nouveau mot de passe',
                    'placeholder' => 'Répétez le mot de passe',
                ],
            ],
            'invalid_message' => 'Les mots de passe ne sont pas identiques.',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false, // ce champ ne fait pas parti d l'entité , donc 'mapped' => false (cad on détache ce champ de l'entité).
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
