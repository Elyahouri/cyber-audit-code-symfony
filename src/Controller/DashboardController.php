<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'Admin DashboardController',
        ]);
    }

    #[Route('/company/dashboard', name: 'app_company_dashboard')]
    public function companyDashboard(): Response
    {
        return $this->render('company/dashboard/index.html.twig', [
            'controller_name' => 'Company DashboardController',
        ]);
    }
}
