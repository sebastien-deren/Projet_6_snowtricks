<?php

namespace App\Service;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class FigureService
{
    public function __construct(private readonly FigureRepository $repository,private readonly SluggerInterface $slugger){}

    public function saveFigure(Figure $figure): void
    {
        $this->repository->save($figure,true);
    }
    public function removeFigure(Figure $figure): void
    {
        $this->repository->remove($figure,true);
    }
}
