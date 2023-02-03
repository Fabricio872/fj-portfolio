<?php

namespace App\Controller;

use Carbon\Carbon;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app.')]
class AppController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        Carbon::setLocale($request->getLocale());
        $startDate = Carbon::create(new DateTime('1.12.2017 08:00'));

        return $this->render('app/index.html.twig', [
            'symfonyInterval' => $startDate->longRelativeToNowDiffForHumans(parts: 5)
        ]);
    }
}
