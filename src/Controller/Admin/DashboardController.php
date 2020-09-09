<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Nationalite;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<h1><b>Bliblio API</b></h1>');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('CRUD'),
            MenuItem::linkToCrud('Livre', 'fa fa-file-text', Livre::class),
            MenuItem::linkToCrud('Auteur', 'fa fa-user', Auteur::class),
            MenuItem::linkToCrud('Genre', 'fa fa-file-text', Genre::class),
            MenuItem::linkToCrud('Editeur', 'fa fa-file-text', Editeur::class),
            MenuItem::linkToCrud('Nationalite', 'fa fa-file-text', Nationalite::class),
        ];
    }
}
