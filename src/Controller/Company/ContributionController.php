<?php

namespace App\Controller\Company;

use App\Entity\Contribution;
use App\Entity\Payment;
use App\Form\ContributionType;
use App\Form\PaymentType;
use App\Repository\ContributionRepository;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company/contribution')]
class ContributionController extends AbstractController
{
    #[Route('/declarer', name: 'app_company_contribution_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContributionRepository $contributionRepository): Response
    {
        $contribution = new Contribution();
        $contribution->setCompany($this->getUser()->getCompany());
        $contribution->setYear('2023');

        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contribution->calculate();
            $contributionRepository->save($contribution, true);

            return $this->redirectToRoute('app_company_dashboard', [], Response::HTTP_SEE_OTHER);
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

    #[Route('/{id}/modifier', name: 'app_company_contribution_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contribution $contribution, ContributionRepository $contributionRepository): Response
    {
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contribution->calculate();
            $contributionRepository->save($contribution, true);

            return $this->redirectToRoute('app_company_contribution_show', ['id'=>$contribution->getId()], Response::HTTP_SEE_OTHER);

        }

        return $this->render('company/contribution/edit.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/payer', name: 'app_company_contribution_pay', methods: ['GET', 'POST'])]
    public function pay(Request $request, Contribution $contribution, PaymentRepository $paymentRepository): Response
    {
        $payment = new Payment();
        $payment->setContribution($contribution);
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentRepository->save($payment, true);
            return $this->redirectToRoute('app_company_contribution_show', ['id'=>$contribution->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/payment/new.html.twig', [
            'contribution' => $contribution,
            'payment'=>$payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_contribution_delete', methods: ['POST'])]
    public function delete(Request $request, Contribution $contribution, ContributionRepository $contributionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contribution->getId(), $request->request->get('_token'))) {
            $contributionRepository->remove($contribution, true);
        }

        return $this->redirectToRoute('app_company_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
