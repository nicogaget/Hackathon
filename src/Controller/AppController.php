<?php


namespace App\Controller;


use App\Entity\Rdv;
use App\Entity\User;
use App\Services\GeoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\GeocodingService;

class AppController extends AbstractController
{
    /**
     * @return Response
     * @Route("/",name="app_index")
     */
    public function index(GeocodingService $geocoding)
    {

        // affectation  coordonées du docteur
        $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor"]);

        $entityManager = $this->getDoctrine()->getManager();
        // recupération des autres users et affectation des coordonées si pas deja existantes
        $rdvList = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findAll();
        for ($i = 0; $i < count($rdvList); $i++) {

            $aRdv = $rdvList[$i];
            // affectation coordonées user
            if (!$aRdv->getLongitude()) {
                $gps = $geocoding->addresstoGPS($aRdv->getAdress());
                $lat = $gps["features"][0]['geometry']['coordinates'][1];
                $long = $gps["features"][0]['geometry']['coordinates'][0];
                $aRdv->setLatitude($lat);
                $aRdv->setLongitude($long);
                $entityManager->persist($aRdv);
            }
        }

        for ($i = 0; $i < 10; $i++) {
            $geo = new GeoService();
            $rdv = $rdvList[$i];
            $dctLatitude = $doctor->getCoordX();
            $dctLongitude = $doctor->getCoordY();
            $rdvLat = $rdv->getLatitude();
            $rdvLong = $rdv->getLongitude();
            $distance = $geo->calcDistance($dctLatitude, $dctLongitude, $rdvLat, $rdvLong);
            if($distance < 1 ) $distance = 1;
            $rdv->setDistance($distance);
            $entityManager->persist($rdv);
        }
        $entityManager->flush();



        return $this->render('index.html.twig');
    }
}
