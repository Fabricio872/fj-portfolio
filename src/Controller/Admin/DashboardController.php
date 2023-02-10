<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\PrintedProject;
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
            ->setTitle('Fabricio Jakubec - portfolio')
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Web Projects', 'fas fa-list', WebProject::class);
        yield MenuItem::linkToCrud('Printed Projects', 'fas fa-list', PrintedProject::class);
        yield MenuItem::linkToRoute('User View', 'fas fa-list', 'app.index');
    }
}
