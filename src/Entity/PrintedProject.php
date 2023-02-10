<?php

declare(strict_types=1);

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\PrintedProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties]
#[ORM\Entity(repositoryClass: PrintedProjectRepository::class)]
class PrintedProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Translation $title;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Translation $description;

    #[ORM\Column(length: 255)]
    private string $imagePath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?Translation
    {
        return $this->title;
    }

    public function setTitle(Translation $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?Translation
    {
        return $this->description;
    }

    public function setDescription(Translation $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = (string) $imagePath;

        return $this;
    }
}
