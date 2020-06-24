<?php

namespace App\Controller;

use App\Entity\Rdv;
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
     * @Route("/", name="rdv_index", methods={"GET"})
     */
    public function index(RdvRepository $rdvRepository): Response
    {
        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rdv_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rdv);
            $entityManager->flush();

            return $this->redirectToRoute('rdv_index');
        }

        return $this->render('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rdv_show", methods={"GET"})
     */
    public function show(Rdv $rdv): Response
    {
        return $this->render('rdv/show.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rdv_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rdv $rdv): Response
    {
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rdv_index');
        }

        return $this->render('rdv/edit.html.twig', [
            'rdv' => $rdv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rdv_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rdv $rdv): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rdv->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rdv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rdv_index');
    }
}
