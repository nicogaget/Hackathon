<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    /**
     * @return Response
     * @Route("/patient/index",name="patient_index")
     */
    public function index()
    {
        return $this->render('patient/index.html.twig');
    }

}
