<?php

namespace App\Dto;

use App\Entity\Figure;
use App\Enums\FigureTypesEnum;

class FigureFrontPageDTO
{
    public string $name;
    public string $description;
    public string $slug;
    public FigureTypesEnum $category;
    public ?string $photo;
    public function  __construct(Figure $figure,?string $photo)
    {
        $this->name = $figure->getName();
        $this->slug =$figure->getSlug();
        $this->description = $figure->getDescription();
        $this->category = $figure->getCategory();
        $this->photo =$photo;
    }
}