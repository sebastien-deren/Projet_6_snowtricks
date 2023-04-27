<?php

namespace App\Service\Media;

use App\Entity\Media;
use App\Enums\MediaEnum;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;

class MediaUploader
{
    public function __construct(public SluggerInterface $slugger)
    {
    }

    public function upload(Media $media): void
    {
        if (MediaEnum::VIDEO === $media->getType()) {
            $type = $this->videoTypeGuessr(u($media->getVideo()));
            $media->setType($type);
        }

        $upload = $this->getUploader($media->getType());
        $fileToUpload = $media->getType() === MediaEnum::IMAGE ? $media->getFile() : u($media->getVideo());
        $media->setUrl($upload($fileToUpload));

    }

    private function videoTypeGuessr(UnicodeString $Url): MediaEnum
    {
        if ($Url->containsAny('youtu')) {
            return MediaEnum::YOUTUBE;
        }
        if ($Url->containsAny('daily')) {
            return MediaEnum::DAILYMOTION;
        }
        return MediaEnum::VIDEO;
    }

    private function getUploader(MediaEnum $enum): \Closure
    {
        return match ($enum) {
            MediaEnum::IMAGE => fn(File $file): string => $this->uploadImage($file),
            MediaEnum::DAILYMOTION => fn(UnicodeString $video): string => $this->uploadDailyMotion($video),
            MediaEnum::YOUTUBE => fn(UnicodeString $video): string => $this->uploadYoutube($video),
            MediaEnum::VIDEO => fn(UnicodeString $video): string => $video,
        };

    }

    private function uploadImage(File $file): string
    {
        if ($file instanceof UploadedFile) {
            $originalImageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        } else {
            $originalImageName = $file->getFileName();
        }

        $safeName = $this->slugger->slug($originalImageName);
        return $safeName . uniqid() . '.' . $file->guessExtension();

    }

    private function uploadYoutube(UnicodeString $link): string
    {
        $link= $link->split('&');
        $link = $link[0]->replace('youtu.be','youtube.com');
        if ($link->containsAny('/embed')) {
            return $link;
        }
        if ($link->containsAny('watch?')) {
            return $link->replace('/watch?v=', '/embed/');
        }

        return $link->replace('.com/','.com/embed/');


    }

    private function uploadDailyMotion(UnicodeString $link): string
    {
        if ($link->containsAny('/embed/')) {
            return $link;
        }
        return $link->replace('/video/', '/embed/video/');

    }


}
