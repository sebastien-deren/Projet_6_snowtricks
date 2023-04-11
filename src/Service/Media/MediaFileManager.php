<?php

namespace App\Service\Media;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFileManager
{
    public function __construct(
        private string $mediumFolder,
        private LoggerInterface $logger,
    ){}

    public function saveFile(?UploadedFile $file,string $newName):void{
        $file?->move($this->mediumFolder, $newName);
    }

    public function deleteFile(?string $fileName):void{
        $filePath = $this->mediumFolder . '/' . $fileName??'';
        if (!file_exists($filePath)) {
            return;
        }
        try {
                unlink($filePath) ?? throw new \Exception($filePath);
        } catch (\Exception $exception) {
            $this->logger->error('the media at: {filePath} couldn\'t be removed', [
                'filePath' => $filePath,
            ]);
        }
    }

}