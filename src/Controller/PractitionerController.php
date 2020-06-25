<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use App\Services\GeocodingService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/practitioner")
 */
class PractitionerController extends AbstractController
{
    /**
     * @Route("/list", name="practitioner_list")
     * @param GeocodingService $geocoding
     * @return Response
     */
    public function rdvlist (GeocodingService $geocoding)
    {


        // affectation  coordonées du docteur
        $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor"]);
        $gps=$geocoding->addresstoGPS($doctor->getAdress());
        $coordX = $gps["features"][0]['geometry']['coordinates'][1];
        $coordY = $gps["features"][0]['geometry']['coordinates'][0];
        $doctor->setCoordX($coordX);
        $doctor->setCoordY($coordY);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($doctor);


        // liste des rdv eligible pour le docteur
        $rdvList = [];

        // recupération des autres users et affectation des coordonées si pas deja existantes
        $patients = $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAllNotInlude("Doctor");
        for($i =0 ; $i < count($patients) ; $i++)
        {
            /**
             * @var User[]
             */
            $aPatient = $patients[$i];
            // affectation coordonées user
            if(!$aPatient->getCoordX()) {
                $gps = $geocoding->addresstoGPS($$aPatient->getAdress());
                $coordX = $gps["features"][0]['geometry']['coordinates'][1];
                $coordY = $gps["features"][0]['geometry']['coordinates'][0];
                $aPatient->setCoordX($coordX);
                $aPatient->setCoordY($coordY);
                $entityManager->persist($aPatient);
            }

            // ajout du  rdv a la liste , mettre les test ici
            $rdv = $aPatient->getRdv();
            $rdvList[] =$rdv;

        }
        $entityManager->flush();






        return $this->render('practitioner/list.html.twig', [
            'gps' => $gps,
        ]);
    }
}
