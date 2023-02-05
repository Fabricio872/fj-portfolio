<?php

namespace App\Controller;

use App\Entity\WebProject;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app.')]
class AppController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        Carbon::setLocale($request->getLocale());
        $startDate = Carbon::create(new DateTime('1.12.2017 08:00'));

        $id = explode('-', $request->headers->get('Turbo-Frame'));
        $id = end($id);
        $project = $em->getRepository(WebProject::class)->find((int)$id);
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
        sleep(50);
        return $this->render('app/web-projects-list.html.twig');
    }
}
