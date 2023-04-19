<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureFormSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function onFormEventsPRESUBMIT(FormEvent $event): void
    {
        $figure = $event->getData();
        $form = $event->getForm();
        $slug = $this->slugger->slug($figure['name']);
        if(!empty($figure['slug'])){
            unset($figure['slug']);
            $event->setData($figure);
        }
        $form->add('slug', TextType::class, [
            'empty_data' => $slug,
            "row_attr" =>["hidden"=>''],
        ]);
    }



    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormEventsPRESUBMIT',

        ];
    }
}