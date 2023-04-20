<?php

namespace App\Entity;

use App\Enums\FigureTypesEnum;
use App\Enums\MediaEnum;
use App\Listener\MediaListener;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\EntityListeners([MediaListener::class])]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = 'TBDeleted';

    #[ORM\Column(length: 255)]
    //#[Assert\NotBlank]
    private ?string $type = 'null';

    #[ORM\Column(length: 255, nullable: true)]
    //#[Assert\Url]
    private ?string $url = 'null';

    #[ORM\ManyToOne(inversedBy: 'media')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Figure $figure = null;


    private ?UploadedFile $file =null;
    private ?string $tempName =null;

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        $this->setType(MediaEnum::IMAGE);
        $this->name = $file->getClientOriginalName();
        if (null !== $this->url) {
            $this->tempName = $this->url;
        }
        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setTempName(string $name): self
    {
        $this->tempName = $name;
        return $this;
    }

    public function getTempName(): ?string
    {
        return $this->tempName;
    }

    public
    function getId(): ?int
    {
        return $this->id;
    }

    public
    function getName(): ?string
    {
        return $this->name;
    }

    public
    function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public
    function getType(): MediaEnum
    {
        return MediaEnum::tryFrom($this->type)??MediaEnum::DEFAULT;
    }

    public
    function setType(MediaEnum $type): self
    {
        $this->type = $type->value;

        return $this;
    }

    public
    function getUrl(): ?string
    {
        return $this->url;
    }

    public
    function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public
    function getFigure(): ?Figure
    {
        return $this->figure;
    }

    public
    function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }
}
