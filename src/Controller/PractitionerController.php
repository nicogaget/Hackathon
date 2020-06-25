<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use App\Services\GeocodingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/practitioner")
 */
class PractitionerController extends AbstractController
{
     /** @Route("/", name="practitioner_index")
     * @param GeocodingService $geocoding
     * @return Response
     */
    public function index()
    {
        $pract = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(["lastName" => "Doctor0"]);

        $rdv = $this->getDoctrine()
            ->getRepository(RDV::class)
            ->findBy(["practitioner" => $pract]);
        return $this->render('practitioner/index.html.twig', [
            'rdvs' => $rdv,
        ]);
    }

    /**
     * @Route("/list", name="practitioner_list")
     * @param GeocodingService $geocoding
     * @return Response
     */
    public function rdvlist(GeocodingService $geocoding)
    {
        $gps = $geocoding->addresstoGPS();
        $address = $geocoding->GPStoadress();
        return $this->render('practitioner/list.html.twig', [
            'gps' => $gps,
            'address' => $address,
        ]);
    }

    /**
     * @Route ("/map", name="practitioner_map")
     */
    public function map()
    {
        $rdvs = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findBy(['isActive' => 1]);

        return $this->render('/practitioner/map.html.twig', [
            'rdvs' => $rdvs
        ]);
    }
}
