<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\SettingsRepository;

class IncludesController extends AbstractController
{

    public function categoriesAction(CategoryRepository $repo)
    {
        $categories = $repo->createQueryBuilder('p')
            ->setMaxResults(4)
            ->getQuery()->getResult();

        return $this->render('includes/categories.html.twig', [
            'categories' => $categories
        ]);
    }


    public function footerAction(CategoryRepository $repo, SettingsRepository $repoSetting)
    {

        $setting = $repoSetting->findAll()[0];


        $categories = $repo->createQueryBuilder('p')
            ->setMaxResults(6)
            ->getQuery()->getResult();

        return $this->render('includes/footer.html.twig', [
            'categories' => $categories,
            'setting' => $setting
        ]);
    }


    public function logoAction(SettingsRepository $repoSetting)
    {

        $setting = $repoSetting->findAll()[0];


        return $this->render('includes/logo.html.twig', [
            'setting' => $setting,
        ]);
    }
}
