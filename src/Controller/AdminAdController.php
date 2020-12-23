<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repository, $page, Paginator $paginator): Response
    {
        $paginator->setEntityClass(Ad::class)
            ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $paginator
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !");
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $manager): Response
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvais pas supprimer l'annonce car elle possède deja des réservation !");
        } else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");
        }
        return $this->redirectToRoute('admin_ads_index');

    }
}
