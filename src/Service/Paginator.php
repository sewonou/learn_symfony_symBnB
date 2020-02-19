<?php


namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class Paginator
{
    private $entityClass;

    private $limit = 10 ;

    private  $currentPage = 1 ;

    private $manager;

    private $twig;

    private $route;

    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig,RequestStack $request, $templatePath)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->getPage(),
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getData()
    {
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité' sur laquelle nous devons paginer  ! Utiliser la méthode setEntityClass() de l'objet Paginator");
        }

        $offset = $this->limit * $this->currentPage - $this->limit;

        $repo = $this->manager->getRepository($this->entityClass);

        $data = $repo->findBy([], [], $this->limit, $offset);

        return $data;
    }

    public function getPages()
    {
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité' sur laquelle nous devons paginer  ! Utiliser la méthode setEntityClass() de l'objet Paginator");
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        $pages = ceil($total/$this->limit);
        return $pages;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }


    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this ;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }



    public function setPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }


    public function getRoute()
    {
        return $this->route;
    }


    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }
}