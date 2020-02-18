<?php

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Contact\Contact;
use App\Form\Contact\ContactType;
use App\Repository\PlanRepository;
use App\Repository\CategoryRepository;
use App\Entity\SearchEntity\HomeSearch;
use App\Form\SearchForm\HomeSearchType;
use App\EventListener\ContactNotification;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Repository\EventRepository;

class HomeController extends AbstractController
{
    /**
     *@Route("/", name="home")
     */
    public function index(Request $request, PlanRepository $repo)
    {

        $searchform = new HomeSearch();

        $form = $this->createForm(HomeSearchType::class, $searchform);

        $form->handleRequest($request);

        $shops = $repo->createQueryBuilder('p')->innerJoin('p.category', 'c')->innerJoin('p.ratings', 'r')->where('c.name like :cat')->orderBy('r.rating', 'DESC')->setParameter('cat', '%shop%')->setMaxResults(4)->getQuery()->getResult();
        $hotels = $repo->createQueryBuilder('p')->innerJoin('p.category', 'c')->innerJoin('p.ratings', 'r')->where('c.name like :cat')->setParameter('cat', '%hotel%')->orderBy('r.rating', 'DESC')->setMaxResults(4)->getQuery()->getResult();
        $restaurants = $repo->createQueryBuilder('p')->innerJoin('p.category', 'c')->innerJoin('p.ratings', 'r')->where('c.name like :cat')->setParameter('cat', '%cafe%')->orderBy('r.rating', 'DESC')->setMaxResults(4)->getQuery()->getResult();

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'shops' => $shops,
            'hotels' => $hotels,
            'restaurants' => $restaurants
        ]);
    }


    /**
     * @Route("/products", name="products")
     */
    public function products(ProductRepository $repo, PaginatorInterface $paginator, Request $request)
    {

        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->getQuery(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );


        return $this->render('home/products.html.twig', [
            'products' => $pagination
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function evnets(EventRepository $repo, PaginatorInterface $paginator, Request $request)
    {

        $pagination = $paginator->paginate(
            $repo->createQueryBuilder('p')
                ->getQuery(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );

        return $this->render('home/events.html.twig', [
            'events' => $pagination
        ]);
    }


    /**
     * @Route("/search", name="search.plan")
     */
    public function searchPlan(Request $request, PlanRepository $repo, PaginatorInterface $paginator)
    {

        $plan = $request->get('plan');
        $place = $request->get('place');
        $category = $request->get('category');


        $pagination = $paginator->paginate(
            $repo->findPlanByString($plan, $place, $category),
            $request->query->getInt('page', 1),
            5 /*limit per page*/
        );



        return $this->render('home/list.html.twig', [
            'plans' => $pagination
        ]);
    }

    /**
     * @Route("/contact" , name="contact")
     */
    public function contact(Request $request, ContactNotification $contactNotification)
    {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactNotification->notify($contact);
            $this->addFlash('success', 'message sent successfully!');
            return $this->redirectToRoute('contact');
        }
        return $this->render('home/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
