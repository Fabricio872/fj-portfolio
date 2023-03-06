<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GithubReader;
use Github\Exception\RuntimeException;
use Psr\Http\Client\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/github', name: 'github.')]
class GithubController extends AbstractController
{
    #[Route('/{repoId}', name: 'index')]
    public function index(int $repoId, GithubReader $githubReader, CacheInterface $cache): Response
    {
        try {
            $repo = $githubReader->getRepository($repoId);
        } catch (RuntimeException $exception) {
            throw $this->createNotFoundException();
        }

        try {
            $readme = $cache->get(sprintf('readme-%s', $repo->getName()), function (ItemInterface $item) use ($githubReader, $repo) {
                $item->expiresAfter(DateInterval::createFromDateString('1 day'));
                return $githubReader->getReadme($repo->getName());
            });
        } catch (RuntimeException $exception) {
            return $this->redirect($repo->getHtmlUrl());
        }

        return $this->render('github/index.html.twig', [
            'repo' => $repo,
            'content' => $readme
        ]);
    }
}
