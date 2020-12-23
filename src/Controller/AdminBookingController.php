<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $repository, $page, Paginator $paginator): Response
    {
       $paginator->setEntityClass(Booking::class)
           ->setPage($page);
       //$bookings = $paginator->getData();
       //dd($bookings);

        return $this->render('admin/booking/index.html.twig', [
           'pagination'=>$paginator
            /*
            'pagination' => $paginator->getData(),
            'pages'=> $paginator->getPages(),
            'page'=> $page
            */
        ]);
    }

    /**
     * Permet d'éditer une réservation
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();
            $this->addFlash('success', "La reservation N°: {$booking->getId()} a bien été modifiée !");
            return $this->redirectToRoute('admin_booking_index');
        }
        return $this->render('admin/booking/edit.html.twig', [
           'form'=> $form->createView(),
            'booking'=>$booking
        ]);
    }

    /**
     * Permet de supprimer une réservation
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * @param Booking $booking
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(
        Booking $booking, EntityManagerInterface $manager): Response
    {

        $manager->remove($booking);
        $manager->flush();
        $this->addFlash('success', "La réservation auprès de: {$booking->getBooker()->getFullName()} a bien été supprimé avec succès !");

        return $this->redirectToRoute('admin_booking_index');
    }

}
