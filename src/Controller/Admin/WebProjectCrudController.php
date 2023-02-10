<?php

namespace App\Controller\Admin;

use App\Entity\WebProject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class WebProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WebProject::class;
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        if (Crud::PAGE_DETAIL === $responseParameters->get('pageName')) {
            $responseParameters->setIfNotSet('titleSk', '...');
            $responseParameters->setIfNotSet('titleEn', '...');
            $responseParameters->setIfNotSet('descriptionSk', '...');
            $responseParameters->setIfNotSet('descriptionEn', '...');

            // keys support the "dot notation", so you can get/set nested
            // values separating their parts with a dot:
            $responseParameters->setIfNotSet('bar.foo', '...');
            // this is equivalent to: $parameters['bar']['foo'] = '...'
        }

        return $responseParameters;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            UrlField::new('url'),
            AssociationField::new('title')->renderAsEmbeddedForm(
                TranslationTextCrudController::class, 'Title'
            ),
            AssociationField::new('description')->renderAsEmbeddedForm(
                TranslationTextareaCrudController::class, 'Description'
            ),
        ];
    }
}
