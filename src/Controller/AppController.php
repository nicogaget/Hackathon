<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @return Response
     * @Route("/",name="app_index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
