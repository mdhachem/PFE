<?php

namespace App\Controller\Partner;


use App\Entity\Plan;
use App\Form\CreatePlanType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/partner")
 */
class NewPlanController extends AbstractController
{
    /**
     * @Route("/plan/new", name="partner_plan_create_new")
     */
    public function index(Request $request)
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

            return $this->redirectToRoute('show.plan', ['slug' => $plan->getSlug(), 'id' => $plan->getId()]);
        }

        return $this->render('partner/plan/createPlan.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }
}
