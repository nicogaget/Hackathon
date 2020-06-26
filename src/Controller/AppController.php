<?php


namespace App\Controller;


use App\Entity\Rdv;
use App\Entity\User;
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
        $entityManager->flush();



        return $this->render('index.html.twig');
    }
}
