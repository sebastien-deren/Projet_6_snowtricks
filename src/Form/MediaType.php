<?php

namespace App\Form;

use App\Entity\Media;
use App\Enums\MediaEnum;
use App\EventSubscriber\MediaFormSubscriber;
use App\EventSubscriber\SetMediaFormSubscriber;
use App\Service\MediaService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mediaChoice', ChoiceType::class,
                [
                    'attr'=>['class'=>'choice'],
                    'choices' => [
                        MediaEnum::VIDEO->value => MediaEnum::VIDEO->value,
                        MediaEnum::IMAGE->value => MediaEnum::IMAGE->value],
                    'expanded' => true,
                    'multiple' => false,
                    'mapped' => false,
                    'label'=>false,
                ])
            ->add('file', FileType::class, [
                'row_attr'=>['class'=>'fileField ','style'=>'display:none'],
                'required' => false,
                'label'=>false,
            ])
            ->add('video', UrlType::class, [
                'row_attr'=>['class'=>'videoField ','style'=>'display:none'],
                'required' => false,
                'label'=>false,
            ])
            ->addEventSubscriber(new MediaFormSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }

}
