<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\SearchEntity\UserSearch;
use App\Form\SearchForm\UserSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $search = new UserSearch();
        $form = $this->createForm(UserSearchType::class, $search);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $userRepository->findAllUser($search),
            $request->query->getInt('page', 1),
            10 /*limit per page*/
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_index');
    }
}
