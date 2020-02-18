<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Entity\SearchEntity\CitySearch;
use App\Form\SearchForm\CitySearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin/city")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="city_index", methods={"GET"})
     */
    public function index(CityRepository $cityRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $search = new CitySearch();
        $form = $this->createForm(CitySearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $cityRepository->findAllCity($search),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );



        return $this->render('admin/city/index.html.twig', [
            'cities' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('city_index');
        }

        return $this->render('admin/city/new.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_show", methods={"GET"})
     */
    public function show(City $city): Response
    {
        return $this->render('admin/city/show.html.twig', [
            'city' => $city,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, City $city): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_index', [
                'id' => $city->getId(),
            ]);
        }

        return $this->render('admin/city/edit.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="city_delete", methods={"DELETE"})
     */
    public function delete(Request $request, City $city): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($city);
        $entityManager->flush();


        return $this->redirectToRoute('city_index');
    }
}
