<?php

namespace App\Controller;

use App\Entity\Catergory;
use App\Form\CatergoryType;
use App\Repository\CatergoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catergory")
 */
class CatergoryController extends AbstractController
{
    /**
     * @Route("/", name="catergory_index", methods="GET")
     */
    public function index(CatergoryRepository $catergoryRepository): Response
    {
        return $this->render('catergory/index.html.twig', ['catergories' => $catergoryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="catergory_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $catergory = new Catergory();
        $form = $this->createForm(CatergoryType::class, $catergory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($catergory);
            $em->flush();

            return $this->redirectToRoute('catergory_index');
        }

        return $this->render('catergory/new.html.twig', [
            'catergory' => $catergory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catergory_show", methods="GET")
     */
    public function show(Catergory $catergory): Response
    {
        return $this->render('catergory/show.html.twig', ['catergory' => $catergory]);
    }

    /**
     * @Route("/{id}/edit", name="catergory_edit", methods="GET|POST")
     */
    public function edit(Request $request, Catergory $catergory): Response
    {
        $form = $this->createForm(CatergoryType::class, $catergory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('catergory_edit', ['id' => $catergory->getId()]);
        }

        return $this->render('catergory/edit.html.twig', [
            'catergory' => $catergory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="catergory_delete", methods="DELETE")
     */
    public function delete(Request $request, Catergory $catergory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catergory->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($catergory);
            $em->flush();
        }

        return $this->redirectToRoute('catergory_index');
    }
}
