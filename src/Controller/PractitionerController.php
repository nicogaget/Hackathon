<?php


namespace App\Controller;


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
        $gps=$geocoding->addresstoGPS();
        $address=$geocoding->GPStoadress();
        return $this->render('practitioner/list.html.twig', [
            'gps' => $gps,
            'address' => $address,
        ]);
    }
}
