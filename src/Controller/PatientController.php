<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/patient")
 */
class PatientController extends AbstractController
{
    /**
     * @Route("/confirmation", name="patient_confirmation")
     */
    public function confirmation(): Response
    {
        return $this->render('patient/confirmation.html.twig');
    }
}