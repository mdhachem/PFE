<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Rating;
use App\Form\RatingPlanType;
use App\Repository\EventRepository;
use App\Repository\PhotoRepository;
use App\Repository\RatingRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Booking;
use App\Form\Booking\HotelType;
use App\Form\Booking\RestaurantType;
use App\Form\Booking\OtherBookingType;

class PlanController extends AbstractController
{
    /**
     * @Route("/{slug}-{id}", name="show.plan", requirements={"slug" : "[a-z0-9\-]*"})
     */
    public function showPlan(Plan $plan, string $slug, Request $request, ProductRepository $repoPro, EventRepository $repoEvent)
    {

        $form = null;
        $booking = new Booking();

        if ($plan->getBookings()) {

            if ($plan->getCategory()->getName() == "Hotels") {
                $form = $this->createForm(HotelType::class, $booking);
                $form->handleRequest($request);
            } else if ($plan->getCategory()->getName() == "Restaurants & Café") {
                $form = $this->createForm(RestaurantType::class, $booking);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(OtherBookingType::class, $booking);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $booking->setPlan($plan)
                ->setUser($this->getUser())
                ->setCreatedAt(new \DateTime());

            $em->persist($booking);
            $em->flush();

            $this->addFlash('success', 'Success !');
            $success = $this->get('session')->getFlashBag();


            return $this->redirect($request->getUri());
        }



        $products = $repoPro->createQueryBuilder('p')
            ->where('p.plan =:plan')
            ->setParameter('plan', $plan)
            ->setMaxResults(4)
            ->getQuery()->getResult();


        $events = $repoEvent->createQueryBuilder('p')
            ->where('p.plan =:plan')
            ->setParameter('plan', $plan)
            ->setMaxResults(4)
            ->getQuery()->getResult();

        return $this->render('plan/show.html.twig', [
            'plan' => $plan,
            'products' => $products,
            'events' => $events,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}-{id}/products", name="show.plan.products", requirements={"slug" : "[a-z0-9\-]*"})
     */
    public function showPlanProducts(Plan $plan, string $slug, ProductRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->where('p.plan =:plan')
                ->setParameter('plan', $plan)
                ->getQuery(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );

        return $this->render('plan/products.html.twig', [
            'plan' => $plan,
            'products' => $pagination
        ]);
    }


    /**
     * @Route("/{slug}-{id}/events", name="show.plan.events", requirements={"slug" : "[a-z0-9\-]*"})
     */
    public function showPlanEvents(Plan $plan, string $slug, EventRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->where('p.plan =:plan')
                ->setParameter('plan', $plan)
                ->getQuery(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );

        return $this->render('plan/events.html.twig', [
            'plan' => $plan,
            'events' => $pagination
        ]);
    }

    /**
     * @Route("/{slug}-{id}/photos", name="show.plan.photos", requirements={"slug" : "[a-z0-9\-]*"})
     */
    public function showPlanPhotos(Plan $plan, string $slug, PhotoRepository $repo, PaginatorInterface $paginator, Request $request)
    {


        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->where('p.plan =:plan')
                ->setParameter('plan', $plan)
                ->getQuery(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );

        return $this->render('plan/photos.html.twig', [
            'plan' => $plan,
            'photos' => $pagination
        ]);
    }


    /**
     * @Route("/{slug}-{id}/reviews", name="show.plan.reviews", requirements={"slug" : "[a-z0-9\-]*"})
     */
    public function showPlanReviews(Plan $plan, string $slug, ObjectManager $em, RatingRepository $repo, PaginatorInterface $paginator, Request $request)
    {

        $rating = new Rating();
        $form = $this->createForm(RatingPlanType::class, $rating);
        $form->handleRequest($request);

        $currentUser = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setUser($currentUser)
                ->setPlan($plan)
                ->setCreatedAt(new \DateTime());


            if ($this->isRatingByUser($currentUser, $plan)) {
                $em->merge($rating);
                $em->flush();
            } else {
                $em->persist($rating);
                $em->flush();
            }



            return $this->redirectToRoute('show.plan.reviews', array('slug' => $slug, 'id' => $plan->getId()));
        }


        if ($plan->getSlug() !== $slug) {
            $this->redirectToRoute('show.plan', [
                ' id ' => $plan->getId(),
                ' slug ' => $plan->getSlug()
            ], 301);
        }

        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->where('p.plan =:plan')
                ->setParameter('plan', $plan)
                ->getQuery(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );

        return $this->render('plan/reviews.html.twig', [
            'plan' => $plan,
            'reviews' => $pagination,
            'form' => $form->createView(),
            'nb_one' => $repo->findStarOne($plan->getId()),
            'nb_two' => $repo->findStarTwo($plan->getId()),
            'nb_three' => $repo->findStarThree($plan->getId()),
            'nb_four' => $repo->findStarFour($plan->getId()),
            'nb_five' => $repo->findStarFive($plan->getId()),
            'avg_star' => $repo->findAvgRating($plan->getId())
        ]);
    }

    public function isRatingByUser(User $user, Plan $plan): bool
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($plan->getRatings() as $rating) {
            if ($rating->getUser() === $user) {
                $em->remove($rating);
                $em->flush();
                return true;
            }
        }

        return false;
    }
}
