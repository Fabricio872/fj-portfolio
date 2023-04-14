<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\GithubRepo;
use Github\Client;
use Http\Client\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GithubReader
{
    private readonly Client $client;

    public function __construct(
        HttpClient $httpClient,
        private readonly DenormalizerInterface $denormalizer,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $this->client = Client::createWithHttpClient($httpClient);
    }

    public function getRepositoriesArray(bool $requestTag = false): array
    {
        if (!$requestTag){
            return $this->client->api('user')->repositories($this->parameterBag->get('githubUser'));
        }
        $repos = $this->client->api('user')->repositories($this->parameterBag->get('githubUser'));
        return array_map(function ($repo) {
            $repo['tags'] = $this->getTag($repo['name']);
            if($repo['tags']){
                $repo['latestTag'] = $repo['tags'][0]['name'];
            }
            return $repo;
        }, $repos);
    }

    /**
     * @return GithubRepo[]
     */
    public function getRepositories(bool $requestTag = false): array
    {
        /** @var array<int, GithubRepo> $repos */
        return $this->denormalizer->denormalize(
            $this->getRepositoriesArray($requestTag),
            GithubRepo::class . '[]'
        );
    }

    public function getRepository(int $id): GithubRepo
    {
        return $this->denormalizer->denormalize(
            $this->client->api('repo')->showById($id),
            GithubRepo::class
        );
    }

    public function getReadme(string $repoName): string
    {
        $readme = $this->client->api('repo')->contents()->readme($this->parameterBag->get('githubUser'), $repoName);

        return base64_decode((string) $readme['content'], true);
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
