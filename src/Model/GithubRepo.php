<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

class GithubRepo
{
    private string $htmlUrl;

    private string $name;

    private ?string $description = null;

    private string $stargazersCount;

    private DateTime $pushedAt;

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
