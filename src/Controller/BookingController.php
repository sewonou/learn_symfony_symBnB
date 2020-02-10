<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     *
     * @IsGranted("ROLE_USER")
     * @param $ad Ad
     * @return  Response
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager) :Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $booking->setAd($ad)
                ->setBooker($user);

            if(!$booking->isBookableDates()){
                $this->addFlash(
                    'warning',
                    'Les dates que vous avez choisit ne peuvent être disponible : veuillez choisir une autre date'
                );
            }else{
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true,
                ]);
            }

        }

        return $this->render('booking/book.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     *
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking):Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }
}
