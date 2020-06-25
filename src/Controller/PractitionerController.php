<?php


namespace App\Controller;


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
    /**
     * @Route("/list", name="practitioner_list")
     * @param GeocodingService $geocoding
     * @return Response
     */
    public function rdvlist (GeocodingService $geocoding )
    {
        $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor0"]);

        $gps=$geocoding->addresstoGPS();
        var_dump($gps);
        $address=$geocoding->GPStoadress();
        return $this->render('practitioner/list.html.twig', [
            'gps' => $gps,
            'address' => $address,
        ]);
    }
}
