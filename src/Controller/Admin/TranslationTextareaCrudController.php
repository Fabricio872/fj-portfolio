<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Translation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class TranslationTextareaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Translation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextareaField::new('sk', sprintf('%s sk', $pageName)),
            TextareaField::new('en', sprintf('%s en', $pageName)),
        ];
    }
}
