<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\Paginator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index($page, Paginator $paginator):Response
    {
        $paginator->setEntityClass(Booking::class)
            ->setPage($page)
        ;
        return $this->render('admin/booking/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager):Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking, [
            'validation_groups' => ["Default"]
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n° <strong>{$booking->getId()}</strong> a bien été modifiée !"
            );
            return  $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
           'form' => $form->createView(),
           'booking' => $booking,
        ]);
    }

    /**
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     *
     * @param Booking $booking
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager):Response
    {
        $id = $booking->getId();
        $manager->remove($booking);
        $manager->flush();
        $this->addFlash(
            'success',
            "La réservation <strong>n° {$id}</strong> a bien été supprimée !"
        );

        return  $this->redirectToRoute('admin_bookings_index');
    }
}
