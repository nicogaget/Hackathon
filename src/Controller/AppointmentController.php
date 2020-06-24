<?php


namespace App\Controller;

use App\Entity\Rdv;
use App\Form\AppointmentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    /**
     * @Route("/appointment", name="appointment")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        // Create a new RDV instance
        $rdv = new Rdv();

        $form = $this->createForm(AppointmentType::class, $rdv);
        $form->handleRequest($request);

        // Check if form is submitted and is valid
        // If yes, send the new Action instance to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rdv);
            $entityManager->flush();

            // When the SHOW method is coded, delete this line and uncomment the next two lines
            return $this->redirectToRoute('appointment');

        }

        return $this->render('Appointment/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);

    }
}
