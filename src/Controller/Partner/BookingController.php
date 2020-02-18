<?php

namespace App\Controller\Partner;

use App\Repository\BookingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Booking;

/**
 * @Route("/partner")
 */

class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="booking_partner")
     */
    public function index(BookingRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $currentUser = $this->getUser();

        $booking = $repo->createQueryBuilder('b')
            ->innerJoin('b.plan', 'p')
            ->where('p.user = :user')
            ->setParameter('user', $currentUser)
            ->getQuery();

        $pagination = $paginator->paginate(
            $booking,
            $request->query->getInt('page', 1),
            5 /*limit per page*/
        );


        return $this->render('partner/booking/index.html.twig', [
            'booking' => $pagination
        ]);
    }

    /**
     * @Route("/booking/show/{id}", name="booking_show_partner")
     */
    public function show(Booking $booking, BookingRepository $repo)
    {
        return $this->render('partner/booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
