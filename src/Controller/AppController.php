<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrintedProject;
use App\Entity\WebProject;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        if (! $startDate) {
            throw new Exception("Wrong Start date provided");
        }

        return $this->render('app/index.html.twig', [
            'symfonyInterval' => $startDate->longRelativeToNowDiffForHumans(parts: 5),
            'webProjects' => $em->getRepository(WebProject::class)->findAll(),
            'printedProjects' => $em->getRepository(PrintedProject::class)->findAll()
        ]);
    }

    #[Route("/se", name: 'se')]
    public function spidermanEasteregg(): RedirectResponse
    {
        $this->addFlash('spiderman-easteregg', true);
        return $this->redirectToRoute('app.index');
    }
}
