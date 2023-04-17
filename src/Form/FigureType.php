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
                ])
            ->addEventSubscriber(new FigureFormSubscriber($this->slugger));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }

}
