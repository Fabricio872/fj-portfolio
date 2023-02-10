<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Translation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TranslationTextCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Translation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('sk', sprintf('%s sk', $pageName))->setEmptyData(''),
            TextField::new('en', sprintf('%s en', $pageName))->setEmptyData(''),
        ];
    }
}
