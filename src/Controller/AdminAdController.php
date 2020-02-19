<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Paginator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     *
     */
    public function index($page, Paginator $paginator):Response
    {
        $paginator->setEntityClass(Ad::class)
            ->setPage($page)
        ;


        return $this->render('admin/ad/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @param Request $request
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager):Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'form' =>$form->createView(),
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager):Response
    {
        if(count($ad->getBookings() > 0)){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimé l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déja des réservation !"
            );
        }else{
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }


        return $this->redirectToRoute('admin_ads_index');
    }
}
