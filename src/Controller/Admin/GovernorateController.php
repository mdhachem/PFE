<?php

namespace App\Controller\Admin;

use App\Entity\Governorate;
use App\Form\GovernorateType;
use App\Repository\GovernorateRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\SearchEntity\GovernorateSearch;
use App\Form\SearchForm\GovernorateSearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/governorate")
 */
class GovernorateController extends AbstractController
{
    /**
     * @Route("/", name="governorate_index", methods={"GET"})
     */
    public function index(GovernorateRepository $governorateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new GovernorateSearch();
        $form = $this->createForm(GovernorateSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $governorateRepository->findAllGovernorate($search),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return $this->render('admin/governorate/index.html.twig', [
            'governorates' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="governorate_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $governorate = new Governorate();
        $form = $this->createForm(GovernorateType::class, $governorate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($governorate);
            $entityManager->flush();

            return $this->redirectToRoute('governorate_index');
        }

        return $this->render('admin/governorate/new.html.twig', [
            'governorate' => $governorate,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="governorate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Governorate $governorate): Response
    {
        $form = $this->createForm(GovernorateType::class, $governorate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('governorate_index', [
                'id' => $governorate->getId(),
            ]);
        }

        return $this->render('admin/governorate/edit.html.twig', [
            'governorate' => $governorate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="governorate_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Governorate $governorate): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($governorate);
        $entityManager->flush();


        return $this->redirectToRoute('governorate_index');
    }
}
