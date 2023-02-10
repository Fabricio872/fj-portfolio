<?php

namespace App\Controller;

use App\Entity\WebProject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/web-project', name: 'web_project.')]
class WebProjectController extends AbstractController
{
    #[Route('/delete/{id}', name: 'remove')]
    public function remove(WebProject $webProject, EntityManagerInterface $em): Response
    {
        $em->remove($webProject);
        $em->flush();

        $this->addFlash('success', sprintf('Removed Web Project: %s', $webProject->getTitle()));

        return $this->redirectToRoute('app.index');
    }
}
