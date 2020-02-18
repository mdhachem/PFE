<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PlanRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}-{id}", name="show.plans.by.category", requirements={"slug" : "[a-z0-9\-]*"})
     */
    public function index(Category $category, string $slug, Request $request, PlanRepository $repo, PaginatorInterface $paginator)
    {


        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->where("p.category =:cat")
                ->setParameter("cat", $category->getId())->getQuery(),
            $request->query->getInt('page', 1),
            5 /*limit per page*/
        );

        return $this->render('category/show.html.twig', [
            'plans' => $pagination
        ]);
    }
}
