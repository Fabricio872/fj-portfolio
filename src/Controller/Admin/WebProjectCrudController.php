<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\WebProject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class WebProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WebProject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if ($pageName === Crud::PAGE_EDIT) {
        }

        return [
            UrlField::new('url'),
            AssociationField::new('title')->renderAsEmbeddedForm(
                TranslationTextCrudController::class,
                'Title'
            ),
            AssociationField::new('description')->renderAsEmbeddedForm(
                TranslationTextareaCrudController::class,
                'Description'
            ),
        ];
    }
}
