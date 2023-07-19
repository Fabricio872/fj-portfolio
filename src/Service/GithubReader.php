<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GithubRepo;
use DateTimeImmutable;
use Github\Client;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class GithubReader
{
    private readonly Client $client;

    public function __construct(
        ClientInterface $httpClient,
        private readonly DenormalizerInterface $denormalizer,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $this->client = Client::createWithHttpClient($httpClient);
    }

    public function getRepositoriesArray(bool $requestTag = false): array
    {
        if (! $requestTag) {
            return $this->client->api('user')->repositories($this->parameterBag->get('githubUser'));
        }
        $repos = $this->client->api('user')->repositories($this->parameterBag->get('githubUser'));
        return array_map(function ($repo) {
            $repo['tags'] = $this->getTag($repo['name']);
            if ($repo['tags']) {
                $repo['latestTag'] = $repo['tags'][0]['name'];
            }
            return $repo;
        }, $repos);
    }

    /**
     * @return GithubRepo[]
     * @throws ExceptionInterface
     */
    public function getRepositories(bool $requestTag = false): array
    {
        /** @var array<int, GithubRepo> $repos */
        return $this->denormalizer->denormalize(
            $this->getRepositoriesArray($requestTag),
            GithubRepo::class . '[]',
            context: [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractObjectNormalizer::CALLBACKS => [
                    'pushed_at' => self::dateCallback(...),
                ]
            ]
        );
    }

    public function getRepository(int $id): GithubRepo
    {
        return $this->denormalizer->denormalize(
            $this->client->api('repo')->showById($id),
            GithubRepo::class,
            context: [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractObjectNormalizer::CALLBACKS => [
                    'pushed_at' => self::dateCallback(...),
                ]
            ]
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

    public static function dateCallback(string $innerObject, string $outerObject, string $attributeName, string $format = null, array $context = []): DateTimeImmutable
    {
        return new DateTimeImmutable($innerObject);
    }
}
