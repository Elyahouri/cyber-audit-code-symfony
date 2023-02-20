<?php

namespace App\Controller\Company;

use App\Entity\Contribution;
use App\Form\ContributionType;
use App\Repository\ContributionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company/contribution')]
class ContributionController extends AbstractController
{
    #[Route('/new', name: 'app_company_contribution_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContributionRepository $contributionRepository): Response
    {
        $contribution = new Contribution();
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contributionRepository->save($contribution, true);

            return $this->redirectToRoute('app_company_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/contribution/new.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_contribution_show', methods: ['GET'])]
    public function show(Contribution $contribution): Response
    {
        return $this->render('company/contribution/show.html.twig', [
            'contribution' => $contribution,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_contribution_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contribution $contribution, ContributionRepository $contributionRepository): Response
    {
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contributionRepository->save($contribution, true);

            return $this->redirectToRoute('app_company_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/contribution/edit.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_contribution_delete', methods: ['POST'])]
    public function delete(Request $request, Contribution $contribution, ContributionRepository $contributionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contribution->getId(), $request->request->get('_token'))) {
            $contributionRepository->remove($contribution, true);
        }

        return $this->redirectToRoute('app_company_contribution_index', [], Response::HTTP_SEE_OTHER);
    }
}
