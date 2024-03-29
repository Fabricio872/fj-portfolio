<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\GithubRepo;
use App\Entity\PrintedProject;
use App\Entity\WebProject;
use App\Exception\InvalidConfigParameterTypeException;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app.')]
class AppController extends AbstractController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        Carbon::setLocale($request->getLocale());
        $symfonyStartDate = $this->parameterBag->get('symfonyStartDate');
        if (! is_string($symfonyStartDate)) {
            throw new InvalidConfigParameterTypeException('symfonyStartDate', 'string');
        }
        $startDate = Carbon::create(new DateTime($symfonyStartDate));
        if (! $startDate) {
            throw new Exception("Wrong Start date provided");
        }

        return $this->render('app/index.html.twig', [
            'symfonyInterval' => $startDate->longRelativeToNowDiffForHumans(parts: 5),
            'webProjects' => $em->getRepository(WebProject::class)->findAll(),
            'printedProjects' => $em->getRepository(PrintedProject::class)->findAll(),
            'githubProjects' => $em->getRepository(GithubRepo::class)->findBy([], ["pushedAt" => "DESC"])
        ]);
    }

    #[Route("/se", name: 'se')]
    public function spidermanEasteregg(): RedirectResponse
    {
        $this->addFlash('spiderman-easteregg', true);
        return $this->redirectToRoute('app.index');
    }
}
