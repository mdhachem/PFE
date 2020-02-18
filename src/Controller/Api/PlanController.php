<?php

namespace App\Controller\Api;


use App\Repository\PlanRepository;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class PlanController extends AbstractController
{

    /**
     * @Route("/api/plans", name="api.plans", methods={"POST"})
     */
    public function showPlans(PlanRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $plans = $repo->createQueryBuilder('p')
            ->innerJoin('p.city', 'ci')
            ->innerJoin('ci.governorate', 'g')
            ->select('p.id, p.name,p.description, p.address, p.image, ci.name as city, g.name as governorate')
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

    /**
     * @Route("/api/{id}/plan", methods={"POST"})
     */
    public function showPlanById(PlanRepository $repo, SerializerInterface $serializer, $id)
    {

        $plans = $repo->createQueryBuilder('p')
            ->innerJoin('p.city', 'ci')
            ->innerJoin('ci.governorate', 'g')
            ->innerJoin('p.user', 'u')
            ->select('p.id, p.description, p.startDay, p.finalDay,p.startTime,p.finalTime, p.name, p.address, ci.name as city, g.name as governorate, p.image, p.telephone, u.email')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $data = $serializer->serialize($plans, 'json');
        $response = array(
            'code' => true,
            'plan' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
