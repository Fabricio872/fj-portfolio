<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VisitorRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use foroco\BrowserDetection;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $route;

    #[ORM\Column(length: 255)]
    private string $locale;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $at;

    /**
     * @var array<string, string>
     */
    #[ORM\Column]
    private array $params = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    public function __construct()
    {
        $this->at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array<string, string> $params
     */
    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getBrowserDetection(): array
    {
        $browserDetection = new BrowserDetection();

        return $browserDetection->getAll($this->userAgent);
    }
}
