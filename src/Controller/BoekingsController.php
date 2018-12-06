<?php

namespace App\Controller;

use App\Entity\Reservatie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boeking")
 */
class BoekingsController extends AbstractController
{
    /**
     * @Route("/", name="boeken")
     */
    public function index()
    {
        // get the cart from  the session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        // $cart = $session->set('cart', '');
        $boeking = $session->get('boeking', array());

        if (empty($boeking)) {
            $datum = new \DateTime("now");
            $boeking = ['aantal' => 5, 'datumtijd' => $datum];
        }

        // test $tijden->setBegintijd(new \DateTime("now"));
        return $this->render('boekings/index.html.twig', [
            'controller_name' => 'BoekingsController',
            'boeking' => $boeking,
            'form' => null,
        ]);
    }

    /**
     * @Route("/new", name="check")
     */
    public function new(Request $request)
    {
        // get the cart from  the session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        // $cart = $session->set('cart', '');
        $boeking = $session->get('boeking', array());

        if (empty($boeking)) {
            $datum = new \DateTime("now");
            $boeking = ['aantal' => 5, 'datumtijd' => $datum];
            $form = $this->createFormBuilder($boeking)
                ->add('aantal', IntegerType::class)
                ->add('datumtijd', DateTimeType::class)
                // ->add('tafels', CollectionType::class)
                ->add('save', SubmitType::class, array('label' => 'Maak Boeking'))
                ->getForm();
        } else {
            $form = $this->createFormBuilder($boeking)
                ->add('aantal', IntegerType::class)
                ->add('datumtijd', DateTimeType::class)
                //->add('tafels', CollectionType::class)
                ->add('save', SubmitType::class, array('label' => 'Maak Boeking'))
                ->getForm();
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $boeking = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()
                ->getRepository(Reservatie::class);

            //$tosub = new DateInterval('PT4H');
            $start = $boeking['datumtijd'];
            $eind = $boeking['datumtijd'];
            $start->modify('-4 hours');
            $eind->modify('+4 hours');
                dump($start);
            $query = $repository->createQueryBuilder('p')
                ->where('p.datumTijd BETWEEN :startDateTime AND :endDateTime')
                ->setParameter('startDateTime', $start)
                ->setParameter('endDateTime', $eind)
                ->getQuery();

            $reservaties = $query->getResult();
            $tafels = $em->getRepository('App:Tafel')->findAll();

            $rtafel = [];
            foreach ($reservaties as $reservatie) {
                // per reservatie  een array vullen met tafels...
                foreach ($reservatie->getTafel() as $tafel) {
                    array_push($rtafel, $tafel->getId());
                }
            }

            // $rtafel bevat gereserveerde tafels. $tafels alle tafels.
            foreach ($tafels as $key => $tafel) {
                foreach ($rtafel as $rt) {
                    if ($tafel->getId() == $rt) {
                        unset($tafels[$key]);  // verwijder de bezette tafel.
                    }
                }
            }

            $boeking['tafels'] = $tafels;
            $session->set('boeking', $boeking);

            return $this->redirectToRoute('check');
        }

        return $this->render('boekings/index.html.twig', array(
            'controller_name' => 'BoekingsController',
            'boeking' => $boeking,
            'form' => $form->createView(),
        ));
    }
}