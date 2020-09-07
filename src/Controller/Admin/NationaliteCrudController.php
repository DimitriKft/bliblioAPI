<?php

namespace App\Controller\Admin;

use App\Entity\Nationalite;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NationaliteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Nationalite::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
