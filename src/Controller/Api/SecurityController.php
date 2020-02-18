<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\Validate;
use App\Service\TokenGenerator;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class SecurityController extends AbstractController
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/api/register", methods={"POST"}))
     */
    public function register(Request $request, SerializerInterface $serializer, Validate $validate, ObjectManager $em, UserRepository $repo)
    {
        $user = new User();
        $user->setEmail($request->get('email'))
            ->setRoles("ROLE_USER")
            ->setFirstName($request->get('first_name'))
            ->setLastName($request->get('last_name'))
            ->setCreatedAt(new \DateTime());

        $user->setPassword($this->encoder->encodePassword($user, $request->get('password')));


        $response = $validate->validateRequest($user);

        if (!empty($response)) {
            $data = $serializer->serialize($response, 'json');
            return $this->json([
                'code' => false,
                'message' => json_decode($data)
            ], 200);
        }

        $data = $serializer->serialize($user, 'json');
        $em->persist($user);
        $em->flush();

        return $this->json([
            'code' => true,
            'message' => 'success',
            'user' => json_decode($data)
        ], 200);
    }

    /**
     * @Route("/api/login", methods={"POST"}))
     */
    public function login(Request $request, SerializerInterface $serializer, UserRepository $repo)
    {
        $user = $repo->createQueryBuilder('u')
            ->select('u.id,u.image,u.password,u.email ,u.roles,u.firstName,u.lastName,u.telephone, u.address')
            ->Where('u.email =:email')
            ->setParameter('email', $request->get('email'))
            ->getQuery()->getResult();

        $u = new User();
        $u->setPassword($user[0]['password']);



        if (!$this->encoder->isPasswordValid($u, $request->get('password'))) {
            return $this->json([
                'code' => false,
                'message' => 'Password incorrect',
            ], 200);
        }

        $data = $serializer->serialize($user, 'json');
        $response = array(
            'code' => true,
            'user' => json_decode($data)
        );

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/request-password-reset", name="request_password_reset")
     */
    public function requestPasswordReset(Request $request, TokenGenerator $tokenGenerator, Mailer $mailer)
    {

        $repository = $this->getDoctrine()->getRepository(User::class);

        /** @var User $user */
        $user = $repository->findOneBy(['email' => $request->get('email')]);
        if (!$user) {
            return $this->json([
                'code' => false,
                'message' => 'No user found',
            ], 200);
        }

        $token = $tokenGenerator->generateToken();
        $user->setToken($token);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $mailer->sendResetPasswordEmailMessage($user);


        return $this->json([
            'code' => true,
            'message' => 'Message send',
        ], 200);
    }
}
