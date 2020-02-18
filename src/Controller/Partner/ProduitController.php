<?php

namespace App\Controller\Partner;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\ProduitType;
use App\Repository\ProductRepository;
use App\Entity\SearchEntity\ProductSearch;
use App\Form\SearchForm\ProductSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/partner/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProductRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $currentUser = $this->getUser();

        $search = new ProductSearch();
        $form = $this->createForm(ProductSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $produitRepository->findProductByUser($currentUser, $search),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return $this->render('partner/produit/index.html.twig', [
            'produits' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Product();
        $produit->setCreatedAt(new \DateTime());
        $form = $this->createForm(ProductType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('partner/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $produit): Response
    {
        $form = $this->createForm(ProductType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index', [
                'id' => $produit->getId(),
            ]);
        }

        return $this->render('partner/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="produit_delete")
     */
    public function delete(Request $request, Product $produit): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();


        return $this->redirectToRoute('produit_index');
    }
}
