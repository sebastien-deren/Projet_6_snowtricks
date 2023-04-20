<?php

namespace App\Listener;

use App\Entity\Media;
use App\Enums\MediaEnum;
use App\Service\MediaService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PostPersist;
use Doctrine\ORM\Mapping\PostUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaListener
{
    public function __construct(
        public SluggerInterface $slugger,
        public string $imageFolder,
        public MediaService $service,
    ){}
    #[PrePersist]
    #[PreUpdate]
    public function preUpload(Media $media,PrePersistEventArgs|PreUpdateEventArgs $eventArgs ): void
    {
        if(realpath($media->getUrl())){
            $media->setTempName($media->getUrl());
        }


        $upload =match ($media->getType()) {
            MediaEnum::IMAGE =>
            function ($file) {
                $originalImageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = $this->slugger->slug($originalImageName);
                return $safeName . uniqid() . '.' . $file->guessExtension();
            },
            MediaEnum::VIDEO => fn($x) => $x,
            MediaEnum::DAILYMOTION => fn($file) => $this->service->dailyMotionEmbedder(u($file)),
            MediaEnum::YOUTUBE => fn($file) => $this->service->youtubeEmbedder(u($file)) ,
        };
        if (null === $media->getFile()) {
            return;
        }

        $media->setUrl($upload($media->getFile()));

    }
#[PostPersist]
#[PostUpdate]

    public function postUpload(Media $media, PostPersistEventArgs|PostUpdateEventArgs $eventArgs): void
    {
        $media->getFile()?->move($this->imageFolder, $media->getUrl());
        if(null !== $media->getTempName()){
            $oldFile=$this->imageFolder.'/'.$media->getTempName();
            if(file_exists($oldFile)){
                unlink(realpath($oldFile));
            }
        }

    }


}