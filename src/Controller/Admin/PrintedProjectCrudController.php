<?php

namespace App\Controller\Admin;

use App\Entity\PrintedProject;
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
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('title')->renderAsEmbeddedForm(
                TranslationTextCrudController::class, 'Title'
            ),
            AssociationField::new('description')->renderAsEmbeddedForm(
                TranslationTextareaCrudController::class, 'Description'
            ),
            ImageField::new('imagePath', 'Image')->setUploadDir('/var/data')->setBasePath('/image/storage/printed/')
        ];
    }
}
