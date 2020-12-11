<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repository): Response
    {
        //$session->set('panier', 'mon panier ');
        //dump($session->get('panier'));
        $ads = $repository->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Permet de créer une nouvelle annonce
     * @Route ("ads/new" , name="ads_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);
        //faire le lien avec les information du formulaire et les champ de la class Ad
        $form->handleRequest($request);
        //dump($ad);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée ! "
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'éditer une nouvelle annonce
     * @Route ("ads/{slug}/edit" , name="ads_edit")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        //faire le lien avec les information du formulaire et les champ de la class Ad
        $form->handleRequest($request);
        //dump($ad);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash('success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modofiée ! "
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }


    /**
     * Permet d'afficher une seule annonce
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    public function show($slug, Ad $ad): Response
    {
        //$ad = $repository->findOneBySlug($slug); grace au parameter converter on n'a plus besoin du repository c'es symfony qui s'occupe
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }


}
