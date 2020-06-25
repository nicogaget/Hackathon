<?php


namespace App\Controller;


use App\Entity\Rdv;
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
     * @Route("/", name="practitioner_index")
     * @param GeocodingService $geocoding
     * @return Response
     */
    public function index ()
    {
        $rdv=$this->getDoctrine()
            ->getRepository(RDV::class)
            ->findBy(["practitioner" => 35]);
        return $this->render('practitioner/index.html.twig', [
            'rdvs' => $rdv,
        ]);
    }

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
