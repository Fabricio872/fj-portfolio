<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\WebProject;
use App\Service\ImageOptimizer;
use App\Service\SeleniumImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\s;
use Symfony\Contracts\Cache\CacheInterface;

#[Route('/image', name: 'image.')]
class ImageController extends AbstractController
{
    public function __construct(
        private readonly ImageOptimizer $imageOptimizer,
        private readonly SeleniumImage $seleniumImage,
        private readonly CacheInterface $cache,
        private readonly ParameterBagInterface $bag
    ) {
    }

    #[Route('/{id}', name: 'web-project')]
    public function webProject(WebProject $webProject, Request $request): Response
    {
        $host = $request->server->get('REQUEST_SCHEME') . '://' . $request->getHttpHost();

        if (! str_starts_with((string) $request->server->get('HTTP_REFERER'), $host)) {
            throw $this->createNotFoundException("You cannot access resource outside $host");
        }
        return $this->cache->get(
            sprintf('web-project-image-%s', $webProject->getId()),
            function () use ($webProject) {
                $image = $this->seleniumImage->getImageFromUrl($webProject->getUrl());

                $headers = [
                    'Content-Type' => $this->imageOptimizer->getMime($image),
                    'Content-Disposition' => 'inline; filename="' . s($webProject->getTitle()->getEn())->snake() . '"',
                    'Cache-Control' => 'max-age=290304000, public'
                ];
                $imageWidth = intval($this->bag->get('tinyImageWidth'));

                return new Response(
                    $this->imageOptimizer->resize($image, $imageWidth),
                    200,
                    $headers
                );
            }
        );
    }

    #[Route('/storage/{size}/{imageName}', name: 'uploaded', requirements: ['size' => 'original|tiny|small|medium|big'])]
    public function uploaded(string $imageName, string $size, Request $request): Response
    {
        $imagePath = __DIR__ . '/../../var/data/' . $imageName;
        return $this->localImage($imagePath, $size, $request);
    }

    #[Route('/asset/{size}/{imageName}', name: 'asset', requirements: ['size' => 'original|tiny|small|medium|big'])]
    public function asset(string $imageName, string $size, Request $request): Response
    {
        $imagePath = __DIR__ . '/../../assets/images/' . $imageName;
        return $this->localImage($imagePath, $size, $request);
    }

    private function localImage(string $imagePath, string $size, Request $request): Response
    {
        $host = $request->server->get('REQUEST_SCHEME') . '://' . $request->getHttpHost();
        $pathArray = explode('/', $imagePath);
        $imageName = end($pathArray);

        if (! str_starts_with((string) $request->server->get('HTTP_REFERER'), $host)) {
            throw $this->createNotFoundException("You cannot access resource outside $host");
        }
        if (! file_exists($imagePath)) {
            throw $this->createNotFoundException(sprintf("Image %s Not Found.", $imageName));
        }

        if ($size === 'original') {
            $imageData = file_get_contents($imagePath);
        } else {
            $imageWidth = intval($this->bag->get($size . 'ImageWidth'));
            $imageData = $this->cache->get(
                sprintf('image-%s-%s', $size, md5_file($imagePath)),
                fn () => $this->imageOptimizer->resize(
                    (string) file_get_contents($imagePath),
                    $imageWidth
                )
            );
        }

        $headers = [
            'Content-Type' => $this->imageOptimizer->getMime($imageData),
            'Content-Disposition' => 'inline; filename="' . $imageName . '"',
            'Cache-Control' => 'max-age=290304000, public'
        ];

        return new Response(
            $imageData,
            200,
            $headers
        );
    }
}
