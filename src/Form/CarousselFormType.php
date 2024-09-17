<?php

namespace App\Form;

use App\Entity\Caroussel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CarousselFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photo', FileType::class, [
            'label' => 'Ajouter une image ',
            //'multiple' => true,
           // 'data_class' => null,//explication
            // unmapped means that this field is not associated to any entity property
            'mapped' => true,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'maxSizeMessage' => 'Fichier trop volumineux, taille maximale {{ limit }}',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Seules les images au format JPEG, JPG et PNG sont acceptées',
                ])
            ],
        ])
        ->add('Valider', SubmitType::class, [
            'label' => 'Enregister',
            'attr' => [
                'class' => 'd-block mx-auto col-3 my-3 btn btn-success'
            ]
           ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Caroussel::class,
        ]);
    }
}
