<?php


namespace App\Controller;


use App\Entity\User;
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
        $type = $doctor->getType();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($doctor);


        // recupération des autres users et affectation des coordonées si pas deja existantes
        $patients = $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAllNotInlude("Doctor");
        for($i =0 ; $i < count($patients) ; $i++)
        {
            if(!$patients[$i]->getCoordX()) {
                $gps = $geocoding->addresstoGPS($patients[$i]->getAdress());
                $coordX = $gps["features"][0]['geometry']['coordinates'][1];
                $coordY = $gps["features"][0]['geometry']['coordinates'][0];
                $patients[$i]->setCoordX($coordX);
                $patients[$i]->setCoordY($coordY);
                $entityManager->persist($patients[$i]);
            }
        }
        $entityManager->flush();

        return $this->render('practitioner/list.html.twig', [
            'gps' => $gps,
        ]);
    }
}
