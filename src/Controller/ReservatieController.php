<?php

namespace App\Controller;

use App\Entity\Reservatie;
use App\Form\ReservatieType;
use App\Repository\ReservatieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservatie")
 */
class ReservatieController extends AbstractController
{
    /**
     * @Route("/", name="reservatie_index", methods="GET")
     */
    public function index(ReservatieRepository $reservatieRepository): Response
    {
        return $this->render('reservatie/index.html.twig', ['reservaties' => $reservatieRepository->findAll()]);
    }

    /**
     * @Route("/new", name="reservatie_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $reservatie = new Reservatie();
        $form = $this->createForm(ReservatieType::class, $reservatie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservatie);
            $em->flush();

            return $this->redirectToRoute('reservatie_index');
        }

        return $this->render('reservatie/new.html.twig', [
            'reservatie' => $reservatie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservatie_show", methods="GET")
     */
    public function show(Reservatie $reservatie): Response
    {
        return $this->render('reservatie/show.html.twig', ['reservatie' => $reservatie]);
    }

    /**
     * @Route("/{id}/edit", name="reservatie_edit", methods="GET|POST")
     */
    public function edit(Request $request, Reservatie $reservatie): Response
    {
        $form = $this->createForm(ReservatieType::class, $reservatie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservatie_edit', ['id' => $reservatie->getId()]);
        }

        return $this->render('reservatie/edit.html.twig', [
            'reservatie' => $reservatie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservatie_delete", methods="DELETE")
     */
    public function delete(Request $request, Reservatie $reservatie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservatie->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reservatie);
            $em->flush();
        }

        return $this->redirectToRoute('reservatie_index');
    }
}
