<?php

namespace App\Controller;

use App\Entity\WebProject;
use App\Service\ImageOptimizer;
use App\Service\SeleniumImage;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/', name: 'app.')]
class AppController extends AbstractController
{
    public function __construct(
        private SeleniumImage  $seleniumImage,
        private CacheInterface $cache,
        private ImageOptimizer $imageOptimizer
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        Carbon::setLocale($request->getLocale());
        $startDate = Carbon::create(new DateTime('1.12.2017 08:00'));

        $id = explode('-', $request->headers->get('Turbo-Frame'));
        $id = end($id);
        $project = $em->getRepository(WebProject::class)->find((int)$id);
        $image = null;
        if ($project) {
            return $this->projectList($project);
        }

        return $this->render('app/index.html.twig', [
            'symfonyInterval' => $startDate->longRelativeToNowDiffForHumans(parts: 5),
            'webProjects' => $em->getRepository(WebProject::class)->findAll()
        ]);
    }

    public function projectList(WebProject $project): Response
    {
        return $this->cache->get(
            sprintf('web-project-image-%s', $project->getId()),
            function (ItemInterface $item) use ($project) {
                $image = $this->seleniumImage->getImageFromUrl($project->getUrl());
                return $this->render('app/web-project-item.html.twig', [
                    'webProject' => $project,
                    'image' => base64_encode(
                        $this->imageOptimizer->resize($image, 286)
                    )
                ]);
            }
        );
    }
}
