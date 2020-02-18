<?php

namespace App\Controller\Partner;

use App\Entity\Event;
use App\Entity\PlanEvent;
use App\Form\PlanEventType;
use App\Repository\EventRepository;
use App\Entity\SearchEntity\EventSearch;
use App\Form\SearchForm\EventSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/partner/event")
 */
class PlanEventController extends AbstractController
{


    /**
     * @Route("/", name="plan_event_index", methods={"GET"})
     */
    public function index(EventRepository $EventRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $currentUser = $this->getUser();

        $search = new EventSearch();
        $form = $this->createForm(EventSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $EventRepository->findEventByUser($currentUser, $search),
            $request->query->getInt('page', 1),
            8 /*limit per page*/
        );

        return $this->render('partner/plan_event/index.html.twig', [
            'plan_events' => $pagination,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/new", name="plan_event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $planEvent = new Event();
        $planEvent->setCreatedAt(new \DateTime());
        $form = $this->createForm(PlanEventType::class, $planEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($planEvent);
            $entityManager->flush();

            return $this->redirectToRoute('plan_event_index');
        }

        return $this->render('partner/plan_event/new.html.twig', [
            'plan_event' => $planEvent,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="plan_event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $planEvent): Response
    {
        $form = $this->createForm(PlanEventType::class, $planEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plan_event_index', [
                'id' => $planEvent->getId(),
            ]);
        }

        return $this->render('partner/plan_event/edit.html.twig', [
            'plan_event' => $planEvent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="plan_event_delete")
     */
    public function delete(Request $request, Event $planEvent): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($planEvent);
        $entityManager->flush();


        return $this->redirectToRoute('plan_event_index');
    }
}
