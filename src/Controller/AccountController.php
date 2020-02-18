<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\User\AccountUpdateType;

class AccountController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $userRepository;

    /**
     * AccountController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('App:User');
    }


    /**
     * @Route("/account", name="account")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accountPage(UserRepository $repo, Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $currentUser = $this->getUser();

        $user = $repo->find($currentUser);

        $form = $this->createForm(AccountUpdateType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('account');
        }

        return $this->render('account/account.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/update/profile", name="profile_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = $this->userRepository->find($this->getUser()->getId());

        $this->updateUser($request, $user);

        $this->addFlash('notice', 'Your account has been updated successfully');
        return $this->redirectToRoute('account');
    }

    /**
     * @Route("/account/password", name="change_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = $this->userRepository->find($this->getUser()->getId());
        $encoded = $encoder->encodePassword($user, $request->get('password'));

        $user->setPassword($encoded);
        $this->persistObject($user);

        $this->addFlash('password', 'Your password has been changed successfully');
        return $this->redirectToRoute('account');
    }

    /**
     * @Route("/account/delete", name="delete_account")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAccount(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user = $this->userRepository->find($this->getUser()->getId());
        $this->removeObject($user);

        $session = new Session();
        $session->clear();

        return $this->redirect('/');
    }

    /**
     * Update the database
     * @param $object
     */
    public function persistObject($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    /**
     * Delete object from the database
     * @param $object
     */
    public function removeObject($object)
    {
        $this->entityManager->remove($object);
        $this->entityManager->flush();
    }

    public function updateUser(Request $request, User $user)
    {
        $user->setEmail($request->get('email'));
        $user->setFirstName($request->get('firstname'));
        $user->setLastName($request->get('lastname'));
        $user->setImage($request->get('imageFile'));
        $user->setAddress($request->get('address'));
        $user->setTelephone($request->get('telephone'));
        $this->persistObject($user);
    }
}
