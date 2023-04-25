<?php

namespace App\EventSubscriber;

use App\Enums\MediaEnum;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;

class MediaFormSubscriber implements EventSubscriberInterface
{
    public function onFormPreSubmit(FormEvent $event): void
    {
        $media = $event->getData();
        $enum = MediaEnum::tryFrom($media["mediaChoice"]??null);
        $setField = match ($enum){
            // we normally only have mediaEnum::video
            MediaEnum::VIDEO, MediaEnum::DAILYMOTION, MediaEnum::YOUTUBE => 'video',
            MediaEnum::IMAGE =>'file',
            Default =>null,
        };
        if(null ===$setField){
            $event->setData(null);
            return;
        }
        $newMedia=[];
        $newMedia[$setField]= $media[$setField];
        $event->setData($newMedia);


    }

    public static function getSubscribedEvents(): array
    {
        return [
            'form.pre_submit' => 'onFormPreSubmit',
        ];
    }
}
