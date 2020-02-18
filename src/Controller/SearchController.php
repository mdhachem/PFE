<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Plan;
use App\Repository\CityRepository;
use App\Repository\PlanRepository;
use App\Repository\GovernorateRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     *@Route("/ajaxplan", name="ajax_search_plan")
     */
    public function searchPlan(Request $request, PlanRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $repo->findEntitiesByString($requestString);
        if (!$entities) {
            $result['entities']['error'] = "not found";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));
    }

    /**
     *@Route("/ajaxlocation", name="ajax_search_location")
     */
    public function searchLocation(Request $request, CityRepository $repog)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $entities =  $repog->findEntitiesByString($requestString);
        if (!$entities) {
            $result['entities']['error'] = "not found";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));
    }



    public function getRealEntities($entities)
    {

        foreach ($entities as $entity) {
            $realEntities[$entity->getId()] = $entity->getName();
        }
        return $realEntities;
    }


    /**
     * @Route("/handleSearch/{_query?}", name="handle_search", methods={"POST", "GET"})
     */
    public function handleSearchRequest(Request $request, $_query)
    {
        $em = $this->getDoctrine()->getManager();

        if ($_query) {
            $data = $em->getRepository(Plan::class)->findByName($_query);
        } else {
            $data = $em->getRepository(Plan::class)->findAll();
        }

        // iterate over all the resuls and 'inject' the image inside
        for ($index = 0; $index < count($data); $index++) {
            $object = $data[$index];
            // http://via.placeholder.com/35/0000FF/ffffff
            $object->setImage("http://via.placeholder.com/35/0000FF/ffffff");
        }

        // setting up the serializer 
        $normalizers = [
            new ObjectNormalizer()
        ];

        $encoders =  [
            new JsonEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($data, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
