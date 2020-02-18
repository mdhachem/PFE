<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Message;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message/send/{id}", name="message_send")
     */
    public function send(Plan $plan, Request $request, ObjectManager $em, UserRepository $repo)
    {
        $user = $this->getUser();
        $content = $request->get('content');


        if (empty($content)) {
            $this->addFlash('error', 'faild !');
            $error = $this->get('session')->getFlashBag();

            return $this->redirectToRoute('show.plan', [
                'slug' => $plan->getSlug(),
                'id' => $plan->getId()
            ]);
        }

        $message = new Message();
        $message->setContent($content)
            ->setEmitter($user)
            ->setReceivere($plan->getUser())
            ->setCreatedAt(new \DateTime())
            ->setIsViewed(false);

        $em->persist($message);
        $em->flush();

        $this->addFlash('send', 'Success !');
        $send = $this->get('session')->getFlashBag();

        return $this->redirectToRoute('show.plan', [
            'slug' => $plan->getSlug(),
            'id' => $plan->getId()
        ]);
    }
}
