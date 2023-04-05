<?php

namespace App\Form;

use App\Entity\Figure;
use App\Enums\FigureTypesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category',EnumType::class, ['class'=>FigureTypesEnum::class,
                'choice_label' =>fn(FigureTypesEnum $choice) => $choice->value,
                'expanded'=>true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}