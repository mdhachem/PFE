<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use App\Entity\SearchEntity\ServiceSearch;
use App\Form\SearchForm\ServiceSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="service_index", methods={"GET"})
     */
    public function index(ServiceRepository $serviceRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $search = new ServiceSearch();
        $form = $this->createForm(ServiceSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $serviceRepository->findAllService($search),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return $this->render('admin/service/index.html.twig', [
            'services' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('admin/service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('service_index', [
                'id' => $service->getId(),
            ]);
        }

        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="service_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Service $service): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($service);
        $entityManager->flush();


        return $this->redirectToRoute('service_index');
    }
}
