<?php

namespace App\Controller\Api;


use App\Repository\PlanRepository;
use App\Repository\EventRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class CategoryController extends AbstractController
{

    /**
     * @Route("/api/category", name="api.category", methods={"POST"})
     */
    public function showPlansByCategory(PlanRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $plans = $repo->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->innerJoin('p.city', 'ci')
            ->innerJoin('ci.governorate', 'g')
            ->where('c.name Like :cat')
            ->setParameter('cat', $request->get('category') . '%')
            ->select('p.id, p.name, p.description, p.address, p.image, ci.name as city, g.name as governorate')
            ->setMaxResults(5)
            ->setFirstResult($request->get('page'));

        if ($request->get('search')) {
            $plans = $plans->where('p.name LIKE :search')
                ->setParameter('search', $request->get('search') . '%');
        }

        $plans = $plans->getQuery()->getResult();

        $data = $serializer->serialize($plans, 'json');
        $response = array(
            'code' => true,
            'plans' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
