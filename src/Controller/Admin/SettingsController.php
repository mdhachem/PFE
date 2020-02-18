<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Settings;
use App\Form\SettingsType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\SettingsRepository;

class SettingsController extends AbstractController
{
    /**
     * @Route("/admin/settings", name="admin_settings")
     */
    public function index(Request $request, ObjectManager $em, SettingsRepository $repo)
    {

        $sett = $repo->findAll();
        $settings = new Settings();
        if ($sett) {
            $settings = $sett[0];
        }

        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$sett) {
                $em->persist($settings);
                $em->flush();
            }
            $em->merge($settings);
            $em->flush();
            $this->addFlash('success', 'Updated!');
            $success = $this->get('session')->getFlashBag();
        }


        return $this->render('admin/settings/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
