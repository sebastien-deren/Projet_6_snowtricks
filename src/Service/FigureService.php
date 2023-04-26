<?php

namespace App\Service;

use App\DTO\FigureFrontPageDTO;
use App\Entity\Figure;
use App\Entity\Media;
use App\Enums\MediaEnum;
use App\Repository\FigureRepository;

class FigureService
{
    public function __construct(private readonly FigureRepository $repository){}

    public function saveFigure(Figure $figure): void
    {
        $this->repository->save($figure,true);
    }
    public function removeFigure(Figure $figure): void
    {
        $this->repository->remove($figure,true);
    }

    /**
     * @return FigureFrontPageDTO[]
     */
    public function findAllFront():array
    {
        $figures = $this->repository->findall();
        $callback =fn($arg)=> (new FigureFrontPageDTO($arg, $this->findFrontImage($arg)));
        return array_map($callback(...),$figures);
    }
    public function findFrontImage(Figure $figure):?string
    {
        $medium = $figure->getMedia();
        $media = $medium->findFirst(function(int $key, Media $value): bool {
            return $value->getType() === MediaEnum::IMAGE;});
        return $media?->getUrl();
    }
}
