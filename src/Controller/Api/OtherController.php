<?php


namespace App\Controller\Api;


use App\Entity\Plan;
use App\Entity\Message;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Rating;

class OtherController extends AbstractController
{



    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     *@Route("/api/send/message", name="api.send.message", methods={"POST"})
     */
    public function sendMessage(Request $request, ObjectManager $manager, UserRepository $repo): Response
    {


        $user = $repo->findByEmail($request->get('email'));

        if (empty($user) or !$this->encoder->isPasswordValid($user[0], $request->get('password'))) {
            return $this->json([
                'code' => false,
                'message' => 'Unauthorized'
            ], 403);
        }


        $message = new Message();
        $message->setContent($request->get('content'))
            ->setEmitter($user[0])
            ->setReceivere($repo->findByEmail($request->get('receivere'))[0])
            ->setIsViewed(false)
            ->setCreatedAt(new \DateTime());

        $manager->persist($message);
        $manager->flush();

        return $this->json([
            'code' => true,
            'message' => 'message envoyé',
        ], 200);
    }


    /**
     *@Route("/api/p/rating/{id}", name="api.rating.p", methods={"POST"})
     */
    public function RatingPlan(Plan $plan, Request $request, ObjectManager $manager, UserRepository $repo): Response
    {


        $user = $repo->findByEmail($request->get('email'));

        if (empty($user) or !$this->encoder->isPasswordValid($user[0], $request->get('password'))) {
            return $this->json([
                'code' => false,
                'message' => 'Unauthorized'
            ], 403);
        }


        $rating = new Rating();
        $rating->setCreatedAt(new \DateTime())
            ->setPlan($plan)
            ->setDescription($request->get('content'))
            ->setRating($request->get('rating'))
            ->setUser($user[0]);

        $manager->persist($rating);
        $manager->flush();

        return $this->json([
            'code' => true,
            'message' => 'message envoyé',
        ], 200);
    }
}
