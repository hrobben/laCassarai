<?php

namespace App\Controller;

use App\Entity\Tijden;
use App\Form\TijdenType;
use App\Repository\TijdenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tijden")
 */
class TijdenController extends AbstractController
{
    /**
     * @Route("/", name="tijden_index", methods="GET")
     */
    public function index(TijdenRepository $tijdenRepository): Response
    {
        return $this->render('tijden/index.html.twig', ['tijdens' => $tijdenRepository->findAll()]);
    }

    /**
     * @Route("/new", name="tijden_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $tijden = new Tijden();
/*        $tijden->setBegintijd(new \DateTime("now"));
        $tijden->setEindtijd(new \DateTime("now"));*/
        $form = $this->createForm(TijdenType::class, $tijden);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tijden);
            $em->flush();

            return $this->redirectToRoute('tijden_index');
        }

        return $this->render('tijden/new.html.twig', [
            'tijden' => $tijden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tijden_show", methods="GET")
     */
    public function show(Tijden $tijden): Response
    {
        return $this->render('tijden/show.html.twig', ['tijden' => $tijden]);
    }

    /**
     * @Route("/{id}/edit", name="tijden_edit", methods="GET|POST")
     */
    public function edit(Request $request, Tijden $tijden): Response
    {
        $form = $this->createForm(TijdenType::class, $tijden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tijden_edit', ['id' => $tijden->getId()]);
        }

        return $this->render('tijden/edit.html.twig', [
            'tijden' => $tijden,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tijden_delete", methods="DELETE")
     */
    public function delete(Request $request, Tijden $tijden): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tijden->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tijden);
            $em->flush();
        }

        return $this->redirectToRoute('tijden_index');
    }
}
