<?php

namespace App\Service;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;

class MediaService
{
    public function __construct(private MediaRepository  $repository,
                                private readonly string  $imageFolder,
                                private SluggerInterface $slugger,
    )
    {
    }

    public function uploadImage(UploadedFile $file): string
    {
        $originalImageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($originalImageName);
        $newFilename = $safeName . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move(
                $this->imageFolder,
                $newFilename
            );
        } catch (\Exception $e) {
            dd($e);
        }

        return $newFilename;
    }

    public function youtubeEmbedder(UnicodeString $link): string
    {
        if ($link->containsAny('/embed')) {
            return $link;
        }
        if ($link->containsAny('watch?')) {
            return $link->replace('/watch?v=', '/embed/');
        }

        throw new \Exception('bad youtube link!');

    }

    public function dailyMotionEmbedder(UnicodeString $link): string
    {
        if ($link->containsAny('/embed/')) {
            return $link;
        }
        return $link === $link->replace('/video/', '/embed/video/') ?: throw  new Exception('bad Dailymotion link');
    }


}