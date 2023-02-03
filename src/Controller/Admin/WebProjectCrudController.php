<?php

namespace App\Controller\Admin;

use App\Entity\WebProject;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class WebProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WebProject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
}
