<?php

namespace App\Controller\Partner;

use App\Entity\Plan;
use App\Form\CreatePlanType;
use App\Repository\PlanRepository;
use App\Entity\SearchEntity\PlanSearch;
use App\Form\SearchForm\PlanSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/partner/plan")
 */
class PlanController extends AbstractController
{
    /**
     * @Route("/", name="partner_plan_index", methods={"GET"})
     */
    public function index(PlanRepository $planRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $currentUser = $this->getUser();

        $search = new PlanSearch();
        $form = $this->createForm(PlanSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $planRepository->findPlanByUser($currentUser, $search),
            $request->query->getInt('page', 1),
            20 /*limit per page*/
        );

        return $this->render('partner/plan/index.html.twig', [
            'plans' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="partner_plan_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $plan = new Plan();
        $form = $this->createForm(CreatePlanType::class, $plan);
        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $plan->setUser($currentUser);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->redirectToRoute('partner_plan_index');
        }

        return $this->render('partner/plan/new.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="partner_plan_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plan $plan): Response
    {
        $form = $this->createForm(CreatePlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('partner_plan_index', [
                'id' => $plan->getId(),
            ]);
        }

        return $this->render('partner/plan/edit.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="partner_plan_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plan $plan): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($plan);
        $entityManager->flush();


        return $this->redirectToRoute('partner_plan_index');
    }
}
