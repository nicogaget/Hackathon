<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/patient")
 */
class PatientController extends AbstractController
{
    /**
     * @Route("/confirmation/{id}", name="patient_confirmation")
     */
    public function confirmation(Rdv $rdv): Response
    {
        $patient = $rdv->getPatient();

        return $this->render('patient/confirmation.html.twig', [
            'rdv' => $rdv,
            'patient' => $patient
        ]);
    }
}