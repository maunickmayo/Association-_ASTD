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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
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
            'mapped' => false, // ce champ ne fait pas parti d l'entité , donc 'mapped' => false (cad on détache ce champ de l'entité).
        ])
        ->add('genre', ChoiceType::class, [
            'label' => 'Sexe',
            'expanded' => false,
            'choices' => [
                "Homme" => 'homme',
                "Femme" => 'femme',
                
            ],
            'choice_attr' => [
              "Homme" => ['selected' => true],
            ],
           //'choice_attr' => function ($choice, $key, $value){
           //  return ['class' => 'myCalss'];
           //  },

            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ ne peut être vide'
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
        ->add('zipcode', TextType::class,[
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les conditions.',
                    ]),
                ],
                'label' => 'Accepter les conditions',
            ])
        ;  
      }

   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
             // Configure your form options here
            'data_class' => User::class,
        ]);
    }
}