<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'constraints' => [
                new Regex([
                    'pattern' => '/[A-Za-z]+$/i',
                    'match' => true,
                    'message' =>'Veuillez entrer uniquement des lettres ',
                ]),
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide'
                ]),
            ],   
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new Regex([
                    'pattern' => '/[A-Za-z]+$/i',

                    'match' => true,
                    'message' =>'Veuillez entrer uniquement des lettres ',
                ]),
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide'
                ]),
            ],  
        ])
       
        ->add('email', EmailType::class, [
            'label' => 'Adresse email',
             'attr' => [
                'placeholder' => 'mail@exemple.fr',
             ],
            'constraints' => [
                new Email([
                    'message' => "Votre email n'est pas au bon format : mail@exemple.fr"
                ]),
                new NotBlank([
                    'message' => "Ce champ ne peut être vide"
                ]),
                new Length([
                    'min' => 4,
                    'max' => 180,
                    'minMessage' => "Votre email doit comporter {{ limit }} caractères minimum.",
                    'maxMessage' => "Votre email doit comporter {{ limit }} caractères maximum."
                ]),
            ],
        ])
         
           
            ->add('phone',  TextType::class,[
                'label' => 'Téléphone',
                'attr' => [
                    'class' => '',
                    
                ],
                'constraints' =>[
                    new Regex([
                        'pattern' => '/^[0-9]+$/i',
                        'match' => true,
                        'message' =>'Veuillez entrer uniquement des chiffres et sans espaces',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'minMessage' => "Votre Téléphone doit comporter {{ limit }} caractères minimum.",
                        'maxMessage' => "Votre Téléphone doit comporter {{ limit }} caractères maximum.",
                        
                    ]),
                ],
            ])
            ->add('country', CountryType::class,[
                'label' => 'Pays',
                 /* 'constraints' =>[
                    new Regex([
                        'pattern' => '/[A-Za-z]+$/i',
                        'match' => true,
                        'message' =>'Veuillez entrer uniquement des chiffres',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => "Veuillez entrer au moins {{ limit }} caractères minimum.",
                        'maxMessage' => "Veuillez entrer au plus de {{ limit }} caractères maximum.",
                        
                    ]),
                ],*/
             ])
            ->add('adress', TextType::class,[
                'label' => 'Lieu-dit',
                'attr' => [
                     'placeholder' => 'Rue, Avenue, Boulevard, Voie, Allée, Sente......',
                     ],
                'constraints' =>[
                    new Regex([
                        'pattern' => '/[A-Za-z]+$/i',
                        'match' => true,
                        'message' =>'Veuillez entrer uniquement des lettres ',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 40,
                        'minMessage' => "Veuillez entrer au moins  {{ limit }} caractères minimum.",
                        'maxMessage' => "Veuillez entrer au plus  {{ limit }} caractères maximum.",
                        
                    ]),
                ],            
            ])
            ->add('numberstreet', TextType::class,[
                'label' => 'N° Voie',
                'constraints' =>[
                    new Regex([
                        'pattern' => '/^[0-9]+$/i',
                        'match' => true,
                        'message' =>'Veuillez entrer uniquement des chiffres ',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 10,
                        'minMessage' => "Veuillez entrer au moins  {{ limit }} caractères minimum.",
                        'maxMessage' => "Veuillez entrer au plus  {{ limit }} caractères maximum.",       
                    ]),
                ],       
            ])
            ->add('zipcode', null,[
                'label' => 'Code postal',
                'constraints' =>[
                    new Regex([
                        'pattern' => '/^[0-9]+$/i',
                        'match' => true,
                        'message' =>'Veuillez entrer uniquement des chiffres',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 10,
                        'minMessage' => "Veuillez entrer au moins  de  {{ limit }} caractères minimum.",
                        'maxMessage' => "Veuillez entrer au plus  {{ limit }} caractères maximum.",       
                    ]),
                ],
            ])
            ->add('city', TextType::class,[
                'label' => 'Votre ville',
                'constraints' =>[
                    new Regex([
                        'pattern' => '/[A-Za-z]+$/i',
                        'match' => true,
                        'message' =>'Veuillez entrer uniquement des lettres',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => "Veuillez entrer au moins {{ limit }} caractères minimum.",
                        'maxMessage' => "Veuillez entrer au plus de {{ limit }} caractères maximum.",
          
                    ]),
                ],
            ]) 
        ;  
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
