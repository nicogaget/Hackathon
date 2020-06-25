<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/practitioner")
 */
class PractitionerController extends AbstractController
{
    /**
     * @Route ("/map", name="practitioner_map")
     */
    public function map()
    {
        return $this->render('/practitioner/map.html.twig');
    }
}