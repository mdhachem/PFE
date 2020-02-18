<?php

namespace App\Controller\Partner;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @Route("/partner/photo")
 */
class PhotoController extends AbstractController
{
    /**
     * @Route("/", name="partner_photo_index", methods={"GET"})
     */
    public function index(PhotoRepository $photoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $currentUser = $this->getUser();

        $pagination = $paginator->paginate(
            $photoRepository->findPlanByUser($currentUser),
            $request->query->getInt('page', 1),
            5 /*limit per page*/
        );

        return $this->render('partner/photo/index.html.twig', [
            'photos' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="partner_photo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('partner_photo_index');
        }

        return $this->render('partner/photo/new.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="partner_photo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Photo $photo): Response
    {
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('partner_photo_index', [
                'id' => $photo->getId(),
            ]);
        }

        return $this->render('partner/photo/edit.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="partner_photo_delete")
     */
    public function delete(Request $request, Photo $photo)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($photo);
        $entityManager->flush();

        return $this->redirectToRoute('partner_photo_index');
    }
}
