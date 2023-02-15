<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\PrintedProject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PrintedProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PrintedProject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('title')->renderAsEmbeddedForm(
            TranslationTextCrudController::class,
            'Title'
        );
        yield AssociationField::new('description')->renderAsEmbeddedForm(
            TranslationTextareaCrudController::class,
            'Description'
        );
        if (Crud::PAGE_EDIT === $pageName) {
            yield ImageField::new('imagePath', 'Image')
                ->setUploadDir('/var/data')
                ->setBasePath('/image/storage/printed/')
                ->setRequired(false);
        } else {
            yield ImageField::new('imagePath', 'Image')
                ->setUploadDir('/var/data')
                ->setBasePath('/image/storage/printed/');
        }
    }
}
