<?php

namespace App\Form;

use App\Entity\Figure;
use App\Enums\FigureTypesEnum;
use App\EventSubscriber\FigureFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\EventSubscriber\SetMediaFormSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class FigureType extends AbstractType
{
    public function __construct(private SluggerInterface $slugger){}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description',TextareaType::class,[
                'attr'=>['rows'=>10],
            ])
            ->add('category',EnumType::class, ['class'=>FigureTypesEnum::class,
                'choice_label' =>fn(FigureTypesEnum $choice) => $choice->value,
                'expanded'=>true])
            ->add('media',CollectionType::class,[
                'row_attr'=>['class'=>'mediaForm'],
                'entry_type'=>MediaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype'			=> true,
                'allow_delete'		=> true,
                'by_reference' 		=> false,
                'required'			=> false,
                'label'			=> false,
                ])
            ->addEventSubscriber(new FigureFormSubscriber($this->slugger));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }

}
