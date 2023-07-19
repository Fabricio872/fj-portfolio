<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\GithubRepo;
use App\Service\GithubReader;
use DateInterval;
use Github\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/github', name: 'github.')]
class GithubController extends AbstractController
{
    #[Route('/{id}', name: 'index')]
    public function index(GithubRepo $repo, GithubReader $githubReader, CacheInterface $cache): Response
    {
        try {
            $readme = $cache->get(sprintf('readme-%s', $repo->getName()), function (ItemInterface $item) use ($githubReader, $repo) {
                $item->expiresAfter(DateInterval::createFromDateString('1 week'));
                return $githubReader->getReadme($repo->getName());
            });
        } catch (RuntimeException) {
            return $this->redirect($repo->getHtmlUrl());
        }

        return $this->render('github/index.html.twig', [
            'repo' => $repo,
            'content' => $readme
        ]);
    }
}
