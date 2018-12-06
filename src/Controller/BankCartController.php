<?php

namespace App\Controller;

use App\Entity\BankCart;
use App\Form\BankCartType;
use App\Repository\BankCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/bank/cart")
 */
class BankCartController extends AbstractController
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * @Route("/", name="bank_cart_index", methods="GET")
     */
    public function index(BankCartRepository $bankCartRepository): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->render('bank_cart/index.html.twig', ['bank_carts' => $bankCartRepository->findAll()]);
        } elseif ($this->security->isGranted('ROLE_USER')) {
            return $this->render('bank_cart/index.html.twig', ['bank_carts' => $bankCartRepository->findBy(['userid' => $this->security->getUser()] )]);
        } else {
            return $this->render('message/noAccess.html.twig');
        }

        }

    /**
     * @Route("/new", name="bank_cart_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $bankCart = new BankCart();
        $bankCart->setUserid($this->getUser());
        dump($bankCart);
        $form = $this->createForm(BankCartType::class, $bankCart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bankCart);
            $em->flush();

            return $this->redirectToRoute('bank_cart_index');
        }

        return $this->render('bank_cart/new.html.twig', [
            'bank_cart' => $bankCart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bank_cart_show", methods="GET")
     */
    public function show(BankCart $bankCart): Response
    {
        if (($this->security->isGranted('ROLE_ADMIN')) or ($this->getUser() == $bankCart->getUserid())) {
            return $this->render('bank_cart/show.html.twig', ['bank_cart' => $bankCart]);
        } else {
            return $this->render('message/noAccess.html.twig');
        }
    }

    /**
     * @Route("/{id}/edit", name="bank_cart_edit", methods="GET|POST")
     */
    public function edit(Request $request, BankCart $bankCart): Response
    {
        $form = $this->createForm(BankCartType::class, $bankCart);
        $form->handleRequest($request);

        if ($bankCart->getUserid() <> $this->getUser() and !($this->security->isGranted('ROLE_ADMIN'))) {
            dump($this->getUser());
            return $this->render('message/noAccess.html.twig');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('bank_cart_edit', ['id' => $bankCart->getId()]);
    }

        return $this->render('bank_cart/edit.html.twig', [
            'bank_cart' => $bankCart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bank_cart_delete", methods="DELETE")
     */
    public function delete(Request $request, BankCart $bankCart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bankCart->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bankCart);
            $em->flush();
        }

        return $this->redirectToRoute('bank_cart_index');
    }
}
