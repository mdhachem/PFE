<?php

namespace App\Controller\Partner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlanRepository;
use App\Repository\ProductRepository;
use App\Repository\EventRepository;
use App\Repository\PhotoRepository;

/**
 * @Route("/partner")
 */

class DashboardPartnerController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard_partner")
     */
    public function index(PlanRepository $repoplan, ProductRepository $repop, EventRepository $repoe, PhotoRepository $repoPhoto)
    {
        $currentUser = $this->getUser();

        return $this->render('partner/dashboard.html.twig', [
            'nb_plan' => $repoplan->countPlanByUser($currentUser),
            'nb_product' => $repop->countProductByUser($currentUser),
            'nb_event' => $repoe->countEventByUser($currentUser),
            'nb_photo' => $repoPhoto->countPhotoByUser($currentUser),
        ]);
    }
}
