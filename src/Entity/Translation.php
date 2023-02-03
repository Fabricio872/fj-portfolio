<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class Translation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sk = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $en = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSk(): ?string
    {
        return $this->sk;
    }

    public function setSk(string $sk): self
    {
        $this->sk = $sk;

        return $this;
    }

    public function getEn(): ?string
    {
        return $this->en;
    }

    public function setEn(string $en): self
    {
        $this->en = $en;

        return $this;
    }
}
