<?php

namespace App\Form;

use App\Entity\Media;
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
    public function __construct(private MediaService $service)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file',FileType::class,[
                'required'=>false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
    public function onSubmitData(FormEvent $event):void{

        $media = $event->getData();
        $form = $event->getForm();
        if(!$media){
            return;
        }
        if(!isset($media['image'])){
            return;
        }
        $newFile = $this->service->uploadImage($media['image']);
        $form->setData([$newFile]);
    }
}