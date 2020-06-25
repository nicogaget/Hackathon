<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
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

        // affectation  coordonées du docteur
        $doctor = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor"]);

        // liste des rdv eligible pour le docteur
        $rdvList = $this->getDoctrine()
            ->getRepository(RDV::class)
            ->findAll();
        // recupération des autres users et affectation des coordonées si pas deja existantes


        return $this->render('practitioner/list.html.twig', [
            'rdvs' => $rdvList
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
