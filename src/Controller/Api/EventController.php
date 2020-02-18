<?php

namespace App\Controller\Api;


use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\EventRepository;



class EventController extends AbstractController
{

    /**
     * @Route("/api/events", name="api.events", methods={"POST"})
     */
    public function showEvents(EventRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $events = $repo->createQueryBuilder('e')
            ->innerJoin('e.plan', 'p')
            ->innerJoin('p.city', 'c')
            ->innerJoin('c.governorate', 'g')
            ->select('e.id, e.name, e.dateEvent,e.description, e.image, p.address, c.name as city, g.name as governorate')
            ->setMaxResults(5)
            ->setFirstResult($request->get('page'));

        if ($request->get('search')) {
            $events = $events->where('e.name LIKE :search')
                ->setParameter('search', $request->get('search') . '%');
        }

        $events = $events->getQuery()->getResult();

        $data = $serializer->serialize($events, 'json');
        $response = array(
            'code' => true,
            'events' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }



    /**
     * @Route("/api/{id}/events", methods={"POST"})
     */
    public function showEventsByPlan(EventRepository $repo, SerializerInterface $serializer, Request $request)
    {
        $events = $repo->createQueryBuilder('e')
            ->innerJoin('e.plan', 'p')
            ->innerJoin('p.city', 'c')
            ->innerJoin('c.governorate', 'g')
            ->where('p.id =:id')
            ->setParameter('id', $request->get('id'))
            ->select('e.id, e.name, e.dateEvent,e.description, e.image, p.address, c.name as city, g.name as governorate')
            ->setMaxResults(5)
            ->setFirstResult($request->get('page'));


        $events = $events->getQuery()->getResult();


        $data = $serializer->serialize($events, 'json');
        $response = array(
            'code' => true,
            'events' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }


    /**
     * @Route("/api/event/{id}", methods={"POST"})
     */
    public function showEventById(EventRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $events = $repo->createQueryBuilder('e')
            ->innerJoin('e.plan', 'p')
            ->innerJoin('p.city', 'c')
            ->innerJoin('p.user', 'u')
            ->innerJoin('c.governorate', 'g')
            ->where('e.id =:id')
            ->setParameter('id', $request->get('id'))
            ->select('e.id, e.name, e.dateEvent,e.description, e.image, p.address, c.name as city, g.name as governorate, u.email, u.telephone, p.id as plan')
            ->getQuery()->getResult();


        $data = $serializer->serialize($events, 'json');
        $response = array(
            'code' => true,
            'event' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
