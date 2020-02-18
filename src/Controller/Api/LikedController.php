<?php


namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\UserRepository;
use App\Repository\ProductLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Event;
use App\Repository\EventLikeRepository;

class LikedController extends AbstractController
{


    /**
     *@Route("/api/liked/product/{id}")
     */
    public function isProductLikedByUser(Product $product, Request $request, UserRepository $repo, ProductLikeRepository $likerepo): Response
    {

        $user = $repo->findByEmail($request->get('email'));

        if (empty($user)) {
            return $this->json([
                'code' => 403,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($product->isLikedByUser($user[0])) {
            return $this->json([
                'code' => 200,
                'liked' => true,
                'likes' => $likerepo->count(['product' => $product])
            ], 200);
        }

        return $this->json([
            'code' => 200,
            'liked' => false,
            'likes' => $likerepo->count(['product' => $product])
        ], 200);
    }

    /**
     *@Route("/api/liked/event/{id}")
     */
    public function isEventLikedByUser(Event $event, Request $request, UserRepository $repo, EventLikeRepository $likerepo): Response
    {

        $user = $repo->findByEmail($request->get('email'));

        if (empty($user)) {
            return $this->json([
                'code' => 403,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($event->isLikedByUser($user[0])) {
            return $this->json([
                'code' => 200,
                'liked' => true,
                'likes' => $likerepo->count(['event' => $event])
            ], 200);
        }

        return $this->json([
            'code' => 200,
            'liked' => false,
            'likes' => $likerepo->count(['event' => $event])
        ], 200);
    }
}
