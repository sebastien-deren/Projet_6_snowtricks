<?php

namespace App\Listener;

use App\Entity\Media;
use App\Enums\MediaEnum;
use App\Service\Media\MediaFileManager;
use App\Service\Media\MediaUploader;
use App\Service\MediaService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PostPersist;
use Doctrine\ORM\Mapping\PostRemove;
use Doctrine\ORM\Mapping\PostUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use function Symfony\Component\String\u;

class MediaListener
{
    public function __construct(private readonly MediaUploader    $uploader,
                                private readonly MediaFileManager $fileManager
    )
    {
    }

    #[PrePersist]
    #[PreUpdate]
    public function preUpload(Media $media, PrePersistEventArgs|PreUpdateEventArgs $eventArgs): void
    {
        $this->uploader->upload($media);
    }


    #[PostPersist]
    #[PostUpdate]
    public function postUpload(Media $media, PostPersistEventArgs|PostUpdateEventArgs $eventArgs): void
    {
        $this->fileManager->saveFile($media->getFile(), $media->getUrl());
        $this->fileManager->deleteFile($media->getTempName());
    }


    #[PostRemove]
    public function postDelete(Media $media, PostRemoveEventArgs $eventArgs): void
    {
        $this->fileManager->deleteFile($media->getUrl());
    }

}
