<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use App\Form\User\ResetPasswordType;
use App\Security\LoginFormAuthenticator;
use App\Form\User\RequestResetPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/validation", name="user_")
 */
class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/request-password-reset", name="request_password_reset")
     */
    public function requestPasswordReset(Request $request, TokenGenerator $tokenGenerator, Mailer $mailer)
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createForm(RequestResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $repository = $this->getDoctrine()->getRepository(User::class);

                /** @var User $user */
                $user = $repository->findOneBy(['email' => $form->get('_username')->getData()]);
                if (!$user) {
                    $this->addFlash('warning', 'user.not-found');
                    return $this->render('security/request-password-reset.html.twig', [
                        'form' => $form->createView()
                    ]);
                }

                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $mailer->sendResetPasswordEmailMessage($user);

                $this->addFlash('success', 'user.request-password-link');
                return $this->redirect($this->generateUrl('home'));
            } catch (ValidatorException $exception) { }
        }

        return $this->render('security/request-password-reset.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password")
     */
    public function resetPassword(
        Request $request,
        User $user,
        GuardAuthenticatorHandler $authenticatorHandler,
        LoginFormAuthenticator $loginFormAuthenticator,
        UserPasswordEncoderInterface $encoder
    ) {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('home'));
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setToken(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'user.update.success');

            // automatic login
            return $authenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        return $this->render('security/password-reset.html.twig', ['form' => $form->createView()]);
    }
}
