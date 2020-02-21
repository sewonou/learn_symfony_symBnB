<?php


namespace App\Controller ;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     * @Route("/hello/{prenom}", name="hello")
     * @return Response
     */
    public function hello($prenom = "anonyme")
    {
        return new Response("hello ". $prenom);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepos, UserRepository $userRepo)
    {

        return $this->render('home.html.twig', [
            'ads' => $adRepos->findBestAds(3),
            'users' => $userRepo->findBestUsers(2, 3)
        ]);
    }
}