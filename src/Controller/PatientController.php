<?php


namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use App\Form\RdvType;
use App\Repository\RdvRepository;
use App\Services\GeocodingService;
use App\Services\GeoService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/",name="patient_index")
     */
    public function index()
    {
        $practitioner = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['lastName'=>'doctor']);
        $patient =$this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['lastName'=>'martinot']);

        $rdv = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findOneBy(array('patient'=>$patient));

        return $this->render('patient/index.html.twig', [
            'rdv' => $rdv,
            'patient' => $patient,
            'practitioner' => $practitioner
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
    public function confirmation(Rdv $rdv, GeocodingService $geocoding): Response
    {
        $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor"]);

        $gps = $geocoding->addresstoGPS($rdv->getAdress());
        $lat = $gps["features"][0]['geometry']['coordinates'][1];
        $long = $gps["features"][0]['geometry']['coordinates'][0];
        $rdv->setLatitude($lat);
        $rdv->setLongitude($long);
        $geo = new GeoService();
        $dctLatitude = $doctor->getCoordX();
        $dctLongitude = $doctor->getCoordY();
        $rdvLat = $rdv->getLatitude();
        $rdvLong = $rdv->getLongitude();
        $distance = $geo->calcDistance($dctLatitude, $dctLongitude, $rdvLat, $rdvLong);
        $rdv->setDistance($distance);
        $em = $this->getDoctrine()->getManager();
        $em->persist($rdv);
        $em->flush();

        $patient = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["id" => $rdv->getPatient()]);

        return $this->render('patient/confirmation.html.twig', [
            'rdv' => $rdv,
            'patient' => $patient
        ]);
    }

    /**
     * @return RedirectResponse
     * @Route("/deleteRdv", name="delete_rdv")
     */
    public function deleteRdv()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $patient =$this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['lastName'=>'martinot']);

        $rdv = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findOneBy(array('patient'=>$patient));


            $entityManager->remove($rdv);
            $entityManager->flush();

        return $this->redirectToRoute('patient_index');
    }
}
