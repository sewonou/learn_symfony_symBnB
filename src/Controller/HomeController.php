<?php


namespace App\Controller ;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
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
    public function home()
    {
        return $this->render('home.html.twig', [
            'age' => 18 ,
            ]);
    }
}