<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Repository\PlanRepository;
use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use App\Repository\GovernorateRepository;
use App\Repository\CityRepository;


/**
 * @Route("/admin")
 */

class DashboardAdminController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard_admin")
     */
    public function index(UserRepository $repouser, PlanRepository $repoplan, CategoryRepository $repocat, ServiceRepository $reposer, GovernorateRepository $repogov, CityRepository $repocity)
    {

        return $this->render('admin/dashboard.html.twig', [
            'nb_users' => $repouser->countUser(),
            'nb_plans' => $repoplan->countPlan(),
            'nb_category' => $repocat->countCategory(),
            'nb_services' => $reposer->countServices(),
            'nb_gov' => $repogov->countGov(),
            'nb_cities' => $repocity->countCities()
        ]);
    }
}
