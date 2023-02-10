<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Locale;
use Stringable;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class Translation implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $sk;

    #[ORM\Column(type: Types::TEXT)]
    private string $en;

    public function __toString(): string
    {
        $locale = Locale::getDefault();

        if (! isset($this->$locale)) {
            throw new Exception(sprintf('Not found locale "%s"', $locale));
        }

        return (string) $this->$locale;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSk(): string
    {
        return $this->sk;
    }

    public function setSk(string $sk): self
    {
        $this->sk = $sk;

        return $this;
    }

    public function getEn(): string
    {
        return $this->en;
    }

    public function setEn(string $en): self
    {
        $this->en = $en;

        return $this;
    }
}
