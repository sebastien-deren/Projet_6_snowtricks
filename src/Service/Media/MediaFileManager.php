<?php

namespace App\Service\Media;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFileManager
{
    public function __construct(
        private string          $imageFolder,
        private LoggerInterface $logger,
    ){}

    public function saveFile(?File $file,string $newName):void{
        $file?->move($this->imageFolder, $newName);
    }

    public function deleteFile(?string $fileName):void{
        $filePath = $this->imageFolder . '/' . $fileName??'';
        if (!file_exists($filePath)) {
            return;
        }
        try {
            if(!unlink($filePath)){
                throw new \Exception($filePath);
            }
        } catch (\Exception $exception) {
            $this->logger->error('the media at: {filePath} couldn\'t be removed', [
                'filePath' => $filePath,
            ]);
        }
    }

}
