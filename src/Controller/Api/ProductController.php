<?php

namespace App\Controller\Api;


use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ProductController extends AbstractController
{


    /**
     * @Route("/api/products", methods={"POST"})
     */
    public function showAllProducts(ProductRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $products = $repo->createQueryBuilder('p')
            ->innerJoin('p.plan', 'c')
            ->innerJoin('c.city', 'ci')
            ->innerJoin('ci.governorate', 'g')
            ->select('p.id, p.name, p.description, p.image, p.price, c.address, ci.name as city , g.name as governorate')
            ->setMaxResults(5)
            ->setFirstResult($request->get('page'));


        if ($request->get('search')) {
            $products = $products->where('p.name LIKE :search')
                ->setParameter('search', $request->get('search') . '%');
        }


        $products = $products->getQuery()->getResult();


        $data = $serializer->serialize($products, 'json');
        $response = array(
            'code' => true,
            'products' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }



    /**
     * @Route("/api/{id}/products", methods={"POST"})
     */
    public function showProductsByPlan(ProductRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $products = $repo->createQueryBuilder('p')
            ->innerJoin('p.plan', 'c')
            ->innerJoin('c.city', 'ci')
            ->innerJoin('ci.governorate', 'g')
            ->innerJoin('c.user', 'u')
            ->where('c.id =:id')
            ->setParameter('id', $request->get('id'))
            ->select('p.id, p.name, p.description, p.image, p.price, c.telephone, c.id as plan,u.email, c.address, ci.name as city , g.name as governorate')
            ->setMaxResults(5)
            ->setFirstResult($request->get('page'));


        $products = $products->getQuery()->getResult();


        $data = $serializer->serialize($products, 'json');
        $response = array(
            'code' => true,
            'products' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/product/{id}", methods={"POST"})
     */
    public function showProductById(ProductRepository $repo, SerializerInterface $serializer, Request $request)
    {

        $product = $repo->createQueryBuilder('p')
            ->innerJoin('p.plan', 'c')
            ->innerJoin('c.city', 'ci')
            ->innerJoin('ci.governorate', 'g')
            ->innerJoin('c.user', 'u')
            ->where('p.id =:id')
            ->setParameter('id', $request->get('id'))
            ->select('p.id, p.name, p.description, p.image, p.price, c.telephone, c.id as plan,u.email, c.address, ci.name as city , g.name as governorate')
            ->getQuery()->getResult();

        $data = $serializer->serialize($product, 'json');
        $response = array(
            'code' => true,
            'product' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}
