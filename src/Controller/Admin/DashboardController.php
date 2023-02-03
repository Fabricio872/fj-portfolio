<?php

namespace App\Controller\Admin;

use App\Entity\WebProject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(WebProjectCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('Fabricio Jakubec - portfolio')

            ->generateRelativeUrls()

            ->setLocales(['en'])
            ->setLocales([
                'en', // locale without custom options
            ])
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('The Label', 'fas fa-list', WebProject::class);
    }
}
