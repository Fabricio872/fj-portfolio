<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GithubReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route(path: '/', name: 'sitemap.')]
class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'index')]
    public function index(GithubReader $githubReader, CacheInterface $cache): Response
    {
        $response = $cache->get('sitemap', function (ItemInterface $item) use ($githubReader) {
            $item->expiresAfter(DateInterval::createFromDateString('1 day'));
            return $this->render('partials/sitemap.xml.twig', [
                'repos' => $githubReader->getRepositories()
            ]);
        });

        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}
