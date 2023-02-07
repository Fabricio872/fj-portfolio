<?php

namespace App\Controller;

use App\Entity\ImageVisit;
use App\Entity\WebProject;
use App\Service\ImageOptimizer;
use App\Service\SeleniumImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use function Symfony\Component\String\s;

#[Route('/image', name: 'image.')]
class ImageController extends AbstractController
{
    public function __construct(
        private ImageOptimizer        $imageOptimizer,
        private SeleniumImage         $seleniumImage,
        private CacheInterface        $cache,
        private ParameterBagInterface $bag
    )
    {
    }

    #[Route('/{id}', name: 'web-project')]
    public function webProject(WebProject $webProject, Request $request): Response
    {
        $host = $request->server->get('REQUEST_SCHEME') . '://' . $request->getHttpHost();

//        if (!str_starts_with((string)$request->server->get('HTTP_REFERER'), $host)) {
//            throw $this->createNotFoundException("You cannot access resource outside $host");
//        }
        return $this->cache->get(
            sprintf('web-project-image-%s', $webProject->getId()),
            function () use ($webProject) {
                $image = $this->seleniumImage->getImageFromUrl($webProject->getUrl());

                $headers = [
                    'Content-Type' => $this->imageOptimizer->getMime($image),
                    'Content-Disposition' => 'inline; filename="' . s($webProject->getTitle()->getEn())->snake() . '"',
                    'Cache-Control' => 'max-age=290304000, public'
                ];

                return new Response(
                    $this->imageOptimizer->resize($image, $this->bag->get('webProjectImageWidth')),
                    200,
                    $headers
                );
            }
        );
    }
}
