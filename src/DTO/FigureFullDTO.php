<?php

namespace App\DTO;

use App\Entity\Figure;
use App\Enums\FigureTypesEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;

class FigureFullDTO
{
    public string $name;
    public string $slug;
    public string $description ;
    public FigureTypesEnum $category ;
    public Collection $media;
    public array $messages;

    public function __construct(Figure $figure)
    {
        $this->slug = $figure->getSlug();
        $this->name =$figure->getName();
        $this->media = $figure->getMedia();
        $this->description =$figure->getDescription();
        $this->category =$figure->getCategory();
        $this->media = $figure->getMedia();
        $this->messages = array_map((fn($args)=> new MessageDTO($args)),$figure->getMessages()->toArray());
    }

}