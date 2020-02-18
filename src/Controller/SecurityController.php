<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('home'));
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error

        ]);
    }

    /**
     * @Route("/register", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, Mailer $mailer)
    {

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('home'));
        }


        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->all();
            $role = $data['registration']['role'];

            if ($role == 0) {
                $user->setRoles("ROLE_USER");
            } else {
                $user->setRoles("ROLE_PARTNER");
            }

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
            $mailer->sendEmailMessage($user);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils)
    { }
}
