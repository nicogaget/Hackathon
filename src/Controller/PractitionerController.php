<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use App\Entity\Rdv;
use App\Services\GeocodingService;

use App\Services\GeoService;
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

        // liste des rdv eligible pour le docteur
        $rdvList = $this->getDoctrine()->getRepository(RDV::class)->findAll();
        return $this->render('practitioner/list.html.twig', ['rdvs' => $rdvList]);
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

    /**
     * @Route ("/accept/{id}", name="practitioner_accept_rdv")
     * @param Rdv $rdv
     *
     * @return Response
     */
    public function acceptRDV(RDV $rdv)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $pract = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor"]);
        $rdv->setPractitioner($pract);
        $entityManager->persist($rdv);
        $entityManager->flush();
        return $this->redirectToRoute('practitioner_index');
    }

}
