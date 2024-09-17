<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PhotoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
        ->add('photo', FileType::class, [
            'label' => 'Veuillez "AJOUTER" une photo de profile.',
          //  'attr' => [
              //  'class' => 'd-block mx-auto col-6 my-3 btn btn-warning'
           //  ],
           
            'data_class' => null,//explication
            // unmapped means that this field is not associated to any entity property
            'mapped' => true,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '2024k',
                    'maxSizeMessage' => 'Fichier trop volumineux, taille maximale {{ limit }}',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Seules les images au format JPEG, JPG et PNG sont acceptées',
                ])
            ],
        ])
       ->add('Valider', SubmitType::class, [
            'label' => 'Enregister',
            'attr' => [
                'class' => 'd-block mx-auto col-6 my-3 btn btn-info'
            ]
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
