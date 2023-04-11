<?php

namespace App\Service\Media;

use App\Entity\Media;
use App\Enums\MediaEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;

class MediaUploader
{
    public function __construct( public SluggerInterface $slugger)
    {
    }

    public function upload(Media $media):void{
        if (MediaEnum::VIDEO === $media->getType()) {
            $type = $this->videoTypeGuessr(u($media->getVideo()));
            $media->setType($type);
        }

        $upload = $this->getUploader($media->getType());
        $fileToUpload = $media->getType() === MediaEnum::IMAGE ? $media->getFile() : u($media->getVideo());
        $media->setUrl($upload($fileToUpload));

    }
    private function videoTypeGuessr(UnicodeString $Url):MediaEnum
    {
        if($Url->containsAny('youtu')){
            return MediaEnum::YOUTUBE;
        }
        if($Url->containsAny('daily')){
            return MediaEnum::DAILYMOTION;
        }
        return MediaEnum::VIDEO;
    }
    private function getUploader(MediaEnum $enum): \Closure
    {
        return match ($enum) {
            MediaEnum::IMAGE => fn(UploadedFile $file): string => $this->uploadImage($file),
            MediaEnum::DAILYMOTION => fn(UnicodeString $video): string => $this->uploadDailyMotion($video),
            MediaEnum::YOUTUBE => fn(UnicodeString $video): string => $this->uploadYoutube($video),
            MediaEnum::VIDEO => fn(UnicodeString $video): string => $video,
        };

    }
    private function uploadImage(UploadedFile $file): string
    {
        $originalImageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($originalImageName);
        return $safeName . uniqid() . '.' . $file->guessExtension();

    }

    private function uploadYoutube(UnicodeString $link): string
    {
        if ($link->containsAny('/embed')) {
            return $link;
        }
        if ($link->containsAny('watch?')) {
            return $link->replace('/watch?v=', '/embed/');
        }

        throw new \Exception('bad youtube link!');

    }

    private function uploadDailyMotion(UnicodeString $link): string
    {
        if ($link->containsAny('/embed/')) {
            return $link;
        }
        return $link === $link->replace('/video/', '/embed/video/') ?: throw  new Exception('bad Dailymotion link');
    }


}