<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GithubReader;
use Github\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/github', name: 'github.')]
class GithubController extends AbstractController
{
    #[Route('/{repoId}', name: 'index')]
    public function index(int $repoId, GithubReader $githubReader): Response
    {
        try {
            $repo = $githubReader->getRepository($repoId);
        } catch (RuntimeException $exception){
            throw $this->createNotFoundException();
        }

        return $this->render('github/index.html.twig', [
            'repo' => $repo,
        ]);
    }
}
