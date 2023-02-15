<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: 'sitemap.')]
class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'index')]
    public function index(): Response
    {
        $response = $this->render('partials/sitemap.xml.twig');

        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}
