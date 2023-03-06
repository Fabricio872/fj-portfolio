<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrintedProject;
use App\Entity\WebProject;
use App\Model\GithubRepo;
use App\Service\GithubReader;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/', name: 'app.')]
class AppController extends AbstractController
{
    public function __construct(
        private readonly GithubReader          $githubReader,
        private readonly CacheInterface        $cache,
        private readonly ParameterBagInterface $parameterBag,
        private readonly DenormalizerInterface $denormalizer
    )
    {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        Carbon::setLocale($request->getLocale());
        $startDate = Carbon::create(new DateTime(strval($this->parameterBag->get('symfonyStartDate'))));
        if (!$startDate) {
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

    /**
     * @return array<int, GithubRepo>
     * @throws ExceptionInterface|InvalidArgumentException
     */
    private function getGithubItems(): array
    {
        return $this->cache->get('github_items', function (ItemInterface $item) {
            $item->expiresAfter(DateInterval::createFromDateString('1 hour'));
            $repos = $this->githubReader->getRepositories();

            uasort($repos, fn($a, $b) => $b->getPushedAt() <=> $a->getPushedAt());

            return $repos;
        });
    }
}
