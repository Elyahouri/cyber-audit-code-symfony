<?php

namespace App\Controller;

use App\Repository\ContributionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'Admin DashboardController',
        ]);
    }

    #[Route('/company/dashboard', name: 'app_company_dashboard')]
    public function companyDashboard(ContributionRepository $contributionRepository): Response
    {
        $contributions = $contributionRepository->findBy(['company' => $this->getUser()->getCompany()]);

        $needDeclation = count(array_filter($contributions, function ($c) {
                if ($c->getYear() === "2023") {
                    return $c;
                }
            })) === 0;

        dump($contributions, $needDeclation);
        return $this->render('company/dashboard.html.twig', [
            'contributions' => $contributions,
            'needDeclaration' => $needDeclation
        ]);
    }
}
