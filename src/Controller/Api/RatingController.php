<?php

namespace App\Controller\Api;


use App\Entity\Event;
use App\Entity\Product;
use App\Entity\EventLike;
use App\Entity\ProductLike;
use App\Repository\UserRepository;
use App\Repository\EventLikeRepository;
use App\Repository\ProductLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RatingController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     *@Route("/api/product/{id}/like", name="product_like")
     */
    public function likeProduct(Product $product, Request $request, ObjectManager $manager, ProductLikeRepository $likerepo, UserRepository $repo): Response
    {

        $user = $repo->findByEmail($request->get('email'));

        if (empty($user) or !$this->encoder->isPasswordValid($user[0], $request->get('password'))) {
            return $this->json([
                'code' => 403,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($product->isLikedByUser($user[0])) {
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
            ->setUser($user[0]);

        $manager->persist($like);
        $manager->flush();


        return $this->json([
            'code' => 201,
            'message' => 'Like bien ajoute',
            'likes' => $likerepo->count(['product' => $product])
        ], 200);
    }



    /**
     *@Route("/api/event/{id}/like", name="event_like")
     */
    public function likeEvent(Event $event, Request $request, ObjectManager $manager, EventLikeRepository $likerepo, UserRepository $repo): Response
    {

        $user = $repo->findByEmail($request->get('email'));

        if (empty($user) or !$this->encoder->isPasswordValid($user[0], $request->get('password'))) {
            return $this->json([
                'code' => 403,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($event->isLikedByUser($user[0])) {
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
            ->setUser($user[0]);

        $manager->persist($like);
        $manager->flush();


        return $this->json([
            'code' => 201,
            'message' => 'Like bien ajoute',
            'likes' => $likerepo->count(['event' => $event])
        ], 200);
    }
}
