<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrintedProject;
use App\Entity\WebProject;
use App\Service\GithubReader;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/', name: 'app.')]
class AppController extends AbstractController
{
    public function __construct(
        private readonly GithubReader $githubReader,
        private readonly CacheInterface $cache,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        Carbon::setLocale($request->getLocale());
        $startDate = Carbon::create(new DateTime($this->parameterBag->get('symfonyStartDate')));
        if (! $startDate) {
            throw new Exception("Wrong Start date provided");
        }

        return $this->render('app/index.html.twig', [
            'symfonyInterval' => $startDate->longRelativeToNowDiffForHumans(parts: 5),
            'webProjects' => $em->getRepository(WebProject::class)->findAll(),
            'printedProjects' => $em->getRepository(PrintedProject::class)->findAll(),
            'githubProjects' => $this->getGithubItems()
        ]);
    }

    #[Route("/se", name: 'se')]
    public function spidermanEasteregg(): RedirectResponse
    {
        $this->addFlash('spiderman-easteregg', true);
        return $this->redirectToRoute('app.index');
    }

    private function getGithubItems(): array
    {
        return $this->cache->get('github_items', function (ItemInterface $item) {
            $item->expiresAfter(DateInterval::createFromDateString('1 month'));
            $items = [];
            foreach ($this->githubReader->listRepositories() as $repository) {
                if ($repository['description']) {
                    $items[] = [
                        'url' => $repository['name'],
                        'title' => $repository['name'],
                        'description' => $repository['description'],
                        'stars' => $repository['stargazers_count']
                    ];
                }
            }
            return $items;
        });
    }
}
