<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

class GithubRepo
{
    private int $id;
    private string $htmlUrl;

    private string $name;

    private string $fullName;

    private ?string $description = null;

    private string $stargazersCount;

    private DateTime $pushedAt;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return GithubRepo
     */
    public function setId(int $id): GithubRepo
    {
        $this->id = $id;
        return $this;
    }
    public function getHtmlUrl(): string
    {
        return $this->htmlUrl;
    }

    public function setHtmlUrl(string $htmlUrl): GithubRepo
    {
        $this->htmlUrl = $htmlUrl;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): GithubRepo
    {
        $this->name = $name;
        return $this;
    }


    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): GithubRepo
    {
        $this->fullName = $fullName;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): GithubRepo
    {
        $this->description = $description;
        return $this;
    }

    public function getStargazersCount(): string
    {
        return $this->stargazersCount;
    }

    public function setStargazersCount(string $stargazersCount): GithubRepo
    {
        $this->stargazersCount = $stargazersCount;
        return $this;
    }

    public function getPushedAt(): DateTime
    {
        return $this->pushedAt;
    }

    public function setPushedAt(string $pushedAt): GithubRepo
    {
        $this->pushedAt = new DateTime($pushedAt);
        return $this;
    }

}
