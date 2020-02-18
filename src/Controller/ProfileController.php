<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{

    /**
      * @Route("/profile/{slug}-{id}", name="show.profile" , requirements={"slug" : "[a-z0-9\-]*"} )
    */
    public function showProfile(User $user, string $slug)
    {

        if ($user->getSlug() !== $slug) {
            $this->redirectToRoute('show.plan', [
                ' id ' => $user->getId(),
                ' slug ' => $user->getSlug()
            ], 301);
        }

        return $this->render('home/profile.html.twig', [
            'user' => $user
        ]);
    }
}
