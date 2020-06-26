<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use App\Entity\Rdv;
use App\Services\GeocodingService;
use App\Services\GeoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/practitioner")
 */
class PractitionerController extends AbstractController
{


    private $apiKey;

    /**
     * @Route("/", name="practitioner_index")
     * @param GeocodingService $geocoding
     */
    public function __construct(string $rootPath)
    {
        $dotenv = new Dotenv();
        $this->rootPath = $rootPath;
        $dotenv->load($rootPath . '/.env.local');
        $this->apiKey = $_ENV["API_TOKEN"];
    }

     /**
     * @Route("/", name="practitioner_index")
     * @return Response
     */
    public function index()
    {
        $pract=new User;
        $pract = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["lastName" => "Doctor"]);

        $rdv = $this->getDoctrine()
            ->getRepository(RDV::class)
            ->findBy(["practitioner" => $pract]);

        $nbrFreeRdv = count(
            $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findBy(['practitioner'=> null]));

        if ($nbrFreeRdv > 0){
            $this->addFlash('warning', "$nbrFreeRdv nouvelle(s) demandes(s) de visite sur votre secteur" );
        } else {
            $this->addFlash('warning', "Aucune demande de visite en attente" );
        }


        return $this->render('practitioner/index.html.twig', [
            'rdvs' => $rdv,
            'nbrFreeRdv'=> $nbrFreeRdv
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
        $practitioner = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(["lastName" => "Doctor"]);

        $rdvs = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findBy([
                'isActive' => 1,
                'practitioner' => $practitioner
                ]);

        return $this->render('/practitioner/map.html.twig', [
            'rdvs' => $rdvs,
            'apiKey' => $this->apiKey
        ]);
    }

    /**
     * @Route ("/map/{id}", name="practitioner_map_solo")
     * @param Rdv $rdv
     * @return Response
     */
    public function mapSolo(RDV $rdv)
    {
        $rdvs = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findBy(['id' => $rdv]);

        return $this->render('/practitioner/map.html.twig', [
            'rdvs' => $rdvs,
            'apiKey' => $this->apiKey
        ]);
    }

    /**
     * @Route ("/accept/{id}", name="practitioner_accept_rdv")
     * @param Rdv $rdv
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

    /**
     * @Route ("/waitmap", name="practitioner_waitmap")
     */
    public function waitMap()
    {
        $practitioner = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(["lastName" => "Doctor"]);

        $rdvs = $this->getDoctrine()
            ->getRepository(Rdv::class)
            ->findByNoPract();

        return $this->render('/practitioner/map.html.twig', [
            'rdvs' => $rdvs,
            'apiKey' => $this->apiKey
        ]);
    }

     /*
     * @Route ("/delete/{id}", name="practitioner_delete_rdv")
     * @param Rdv $rdv
     * @return Response
     */
    public function deleteRDV(RDV $rdv)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($rdv);
        $entityManager->flush();

        return $this->redirectToRoute('practitioner_index');
    }

}
