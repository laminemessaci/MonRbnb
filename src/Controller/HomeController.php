<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AdRepository $adRepository, UserRepository $userRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'ads'=>$adRepository->findBestAds(3),
            'users'=>$userRepository->findBestUsers(3)
        ]);
    }
}
