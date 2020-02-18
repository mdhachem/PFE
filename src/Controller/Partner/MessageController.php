<?php

namespace App\Controller\Partner;

use App\Repository\MessageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Message;

/**
 * @Route("/partner")
 */

class MessageController extends AbstractController
{

    /**
     * @Route("/message", name="partner_show_messages")
     */
    public function index(MessageRepository $repo, PaginatorInterface $paginator, Request $request)
    {

        $user = $this->getUser();

        $msg = $repo->createQueryBuilder('m')
            ->innerJoin('m.receivere', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $user)
            ->getQuery();

        $pagination = $paginator->paginate(
            $msg,
            $request->query->getInt('page', 1),
            20 /*limit per page*/
        );

        return $this->render("partner/other/message.html.twig", [
            'messages' => $pagination
        ]);
    }

    /**
     * @Route("/message/{id}", name="message_show_partner")
     */
    public function show(Message $message)
    {

        $message->setIsViewed(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->render("partner/other/showMsg.html.twig", [
            'message' => $message
        ]);
    }
}
