<?php

namespace App\Controller;

use App\Service\Stats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param Stats $stats
     * @return Response
     */
    public function index(Stats $stats): Response
    {
        $statsTotal = $stats->getStats();
        $bestAds = $stats->getAdsStats('DESC');
        $worstAds = $stats->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $statsTotal,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
