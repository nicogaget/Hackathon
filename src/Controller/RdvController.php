<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\User;
use App\Form\RdvType;
use App\Repository\RdvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rdv")
 */
class RdvController extends AbstractController
{
    /**
     * @Route("/", name="rdv_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(["id" => 1]);
            $rdv->setIsActive(1);
            $rdv->setPatient($user);

            $entityManager->persist($rdv);
            $entityManager->flush();

            return $this->redirectToRoute('rdv_new');
        }

        return $this->render('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }
}
