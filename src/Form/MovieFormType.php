<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                 'placeholder'=> 'Enter title...'   
                ),
                'label' =>false,
                'required'=>false
            ])
            ->add('releaseYear', IntegerType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                 'placeholder'=> 'Enter release year...'   
                ),
                'label' =>false,
                'required'=>false
            ])
            ->add('description', TextareaType::class, [
                'attr' => array(
                    'class' => 'bg-transparent block border-b-2 w-full h-60 text-6xl outline-none',
                 'placeholder'=> 'Enter description...'   
                ),
                'label' =>false,
                'required'=>false
            ])
            ->add('imagePath', FileType::class, [
                'label'=>false,
                'required'=> false,
                'mapped'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
               //'data' => $options['data']->getImagePath() ? new \Symfony\Component\HttpFoundation\File\File($options['data']->getImagePath()) : null
                
            ])
          /*  ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
