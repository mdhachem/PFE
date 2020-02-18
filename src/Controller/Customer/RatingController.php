<?php

namespace App\Controller\Customer;

use App\Entity\Event;
use App\Entity\Rating;
use App\Entity\Product;
use App\Entity\EventLike;
use App\Entity\ProductLike;
use App\Repository\EventLikeRepository;
use App\Repository\ProductLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Notification;

class RatingController extends AbstractController
{

    /**
     *@Route("/product/{id}/like", name="customer_product_like")
     */
    public function likeProduct(Product $product, ObjectManager $manager, ProductLikeRepository $likerepo): Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($product->isLikedByUser($user)) {
            $like = $likerepo->findOneBy([
                'product' => $product,
                'user' => $user
            ]);
            $manager->remove($like);

            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likerepo->count(['product' => $product])
            ], 200);
        }



        $like = new ProductLike();
        $like->setProduct($product)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajoute',
            'likes' => $likerepo->count(['product' => $product])
        ], 200);
    }


    /**
     *@Route("/event/{id}/like", name="customer_event_like")
     */
    public function likeEvent(Event $event, ObjectManager $manager, EventLikeRepository $likerepo): Response
    {
        $user = $this->getUser();

        if (!$user or $user->getRoles()[0] != "ROLE_USER") return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($event->isLikedByUser($user)) {
            $like = $likerepo->findOneBy([
                'event' => $event,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likerepo->count(['event' => $event])
            ], 200);
        }

        $like = new EventLike();
        $like->setEvent($event)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();


        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajoute',
            'likes' => $likerepo->count(['event' => $event])
        ], 200);
    }

    /**
     * @Route("/review/delete/{id}", name="plan_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rating $rating): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($rating);
        $entityManager->flush();


        return $this->redirectToRoute($request->getUri());
    }
}
