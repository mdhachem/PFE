<?php

namespace App\Controller\Admin;

use App\Entity\Plan;
use App\Form\PlanType;
use App\Repository\PlanRepository;
use App\Entity\SearchEntity\PlanSearch;
use App\Form\SearchForm\PlanSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/plan")
 */
class PlanController extends AbstractController
{
    /**
     * @Route("/", name="plan_index", methods={"GET"})
     */
    public function index(PlanRepository $planRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $search = new PlanSearch();
        $form = $this->createForm(PlanSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $planRepository->findAllPlan($search),
            $request->query->getInt('page', 1),
            20 /*limit per page*/
        );


        return $this->render('admin/plan/index.html.twig', [
            'plans' => $pagination,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}/edit", name="plan_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plan $plan): Response
    {
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);
        /*
        $fileSystem = new Filesystem();

        if ($plan->getImage()) {
            $fileSystem->remove($this->getParameter('uploads_directory') . '/' . $plan->getImage());
        }
        */
        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $file = $form->get('image')->getData();
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );
                $plan->setImage($fileName);
            }
            */





            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plan_index', [
                'id' => $plan->getId(),
            ]);
        }

        return $this->render('admin/plan/edit.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="plan_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plan $plan): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($plan);
        $entityManager->flush();


        return $this->redirectToRoute('plan_index');
    }
}
