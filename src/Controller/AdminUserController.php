<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users_index")
     * @param   UserRepository $repo
     * @return  Response
     */
    public function index(UserRepository $repo):Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     *
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(User $user,Request $request, ObjectManager $manager ):Response
    {
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'utilisateur <strong>{$user->getId()}</strong> a bien été enregistrée !"
            );
            return $this->redirectToRoute('admin_users_index');
        }
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     *
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager):Response
    {
        $id = $user->getId();
        if($user === $this->getUser()){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimé l'utilisateur courant <strong>{$user->getFullName()}</strong> !"
            );
        }elseif (count($user->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimé l'utilisateur <strong>{$user->getFullName()}</strong> car il dispose de réservations !"
            );
        }elseif (count($user->getAds())>0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimé l'utilisateur <strong>{$user->getFullName()}</strong> car il dispose d'annonces !"
            );
        }elseif (count($user->getComments())>0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimé l'utilisateur <strong>{$user->getFullName()}</strong> car il dispose de commentaires !"
            );
        }else{
           $manager->remove($user);
           $manager->flush();
            $this->addFlash(
                'warning',
                "L'utilisateur <strong>n°{$id}</strong> a bien été supprimée !"
            );
        }
        return $this->redirectToRoute('admin_users_index');
    }
}
