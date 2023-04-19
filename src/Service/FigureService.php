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
       // $this->createSlug($figure);
        $this->repository->save($figure,true);
    }
    private function createSlug(Figure $figure): void
    {
        $slug = $this->slugger->slug($figure->getName());
        $slug = $this->repository->findUniqueSlug($slug);
        $figure->setSlug($slug);
    }
    public function removeFigure(Figure $figure): void
    {
        $this->repository->remove($figure,true);
    }
}
