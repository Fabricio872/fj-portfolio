<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GithubRepoRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GithubRepoRepository::class)]
class GithubRepo
{
    #[ORM\Id]
    #[ORM\CustomIdGenerator]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $htmlUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $stargazersCount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latestTag = null;

    #[ORM\Column]
    private ?DateTimeImmutable $pushedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getHtmlUrl(): ?string
    {
        return $this->htmlUrl;
    }

    public function setHtmlUrl(string $htmlUrl): static
    {
        $this->htmlUrl = $htmlUrl;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStargazersCount(): ?string
    {
        return $this->stargazersCount;
    }

    public function setStargazersCount(string $stargazersCount): static
    {
        $this->stargazersCount = $stargazersCount;

        return $this;
    }

    public function getLatestTag(): ?string
    {
        return $this->latestTag;
    }

    public function setLatestTag(?string $latestTag): static
    {
        $this->latestTag = $latestTag;

        return $this;
    }

    public function getPushedAt(): ?DateTimeImmutable
    {
        return $this->pushedAt;
    }

    public function setPushedAt(DateTimeImmutable $pushedAt): static
    {
        $this->pushedAt = $pushedAt;

        return $this;
    }
}
