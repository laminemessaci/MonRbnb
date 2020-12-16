<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted ("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $booking->setBooker($user)
                ->setAd($ad);
            //dd($booking);

            //Si les dates ne sont pas disponibles, message d'erreur
            if (!$booking->isBookableDates()){
                $this->addFlash('danger',
                "Les dates que vous avez choisi ne sont pas disponibles, merci de modifier la plage des dates !");
            }else {
                //Sinon enregistrement et redirection
                $manager->persist($booking);
                $manager->flush();

                return  $this->redirectToRoute('booking_show', ['id'=>$booking->getId(),
                    'withAlert'=>true
                ]);
            }

        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form'=> $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     * @Route ("/booking/{id}", name="booking_show")
     * @param Booking $booking
     */
    public function show(Booking $booking){

        return $this->render('booking/show.html.twig', [
          'booking'=>  $booking,
        ]);

    }
}
