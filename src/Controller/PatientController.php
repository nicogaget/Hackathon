<?php


namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use App\Form\RdvType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/patient")
 */
class PatientController extends AbstractController
{
    /**
     * @return Response
     * @Route("/index",name="patient_index")
     */
    public function index()
    {
        $rdv = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findOneBy(array('patient'=>2));
        dump($rdv);

        return $this->render('patient/index.html.twig', [
            'rdv'=>$rdv
        ]);
    }

    /**
     * @Route("/rdv", name="patient_rdv", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(["lastName" => "Martinot"]);

            $rdv->setIsActive(1);
            $rdv->setPatient($user);

            $entityManager->persist($rdv);
            $entityManager->flush();

            return $this->redirectToRoute('patient_confirmation', ['id' => $rdv->getId()]);
        }

        return $this->render('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/{id}", name="patient_confirmation")
     * @param Rdv $rdv
     * @return Response
     */
    public function confirmation(Rdv $rdv): Response
    {
        $patient = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["id" => $rdv->getPatient()]);

        return $this->render('patient/confirmation.html.twig', [
            'rdv' => $rdv,
            'patient' => $patient
        ]);
    }

}
