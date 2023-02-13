<?php

declare(strict_types=1);

namespace App\Service;

use Github\Client;
use Http\Client\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GithubReader
{
    private readonly Client $client;

    public function __construct(
        HttpClient $httpClient,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $this->client = Client::createWithHttpClient($httpClient);
    }

    public function listRepositories(): array
    {
        return $this->client->api('user')->repositories($this->parameterBag->get('githubUser'));
    }

    public function getRepository(int $id): array
    {
        return $this->client->api('repo')->showById($id);
    }

    public function getTag(string $repoName): array
    {
        return $this->client->api('repo')->tags($this->parameterBag->get('githubUser'), $repoName);
    }

    public function getCommit(string $repoName, string $commit): array
    {
        return $this->client->api('repo')->commits()->show($this->parameterBag->get('githubUser'), $repoName, $commit);
    }
}
