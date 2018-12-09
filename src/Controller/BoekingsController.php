<?php

namespace App\Controller;

use App\Entity\Reservatie;
use App\Entity\Tafel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                ->add('save', SubmitType::class, array('label' => 'Zoek tafels'))
                ->getForm();
        } else {
            $tafels = [];
            foreach ($boeking['tafels'] as $key => $personen) {
                $tafels[$personen . ' personen '] = $key;
                //array_push($tafels, $key.' -> '.$personen. ' personen ');
            }
            //dump($tafels);
            $form = $this->createFormBuilder($boeking)
                ->add('aantal', IntegerType::class)
                ->add('datumtijd', DateTimeType::class)
                // ->add('tafels', CollectionType::class)
/*                ->add('tafels', ChoiceType::class, [
                    'choices' => $tafels,
                    'multiple' => true,
                ])*/
                ->add('save', SubmitType::class, array('label' => 'Reserveren?'))
                ->getForm();
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $boeking = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()
                ->getRepository(Reservatie::class);

            $start = date("Y-m-d H:m:s", strtotime('-4 hours', $boeking['datumtijd']->getTimestamp()));
            $eind = date("Y-m-d H:m:s", strtotime('+4 hours', $boeking['datumtijd']->getTimestamp()));


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

            return $this->redirectToRoute('book');
        }

        return $this->render('boekings/index.html.twig', array(
            'controller_name' => 'BoekingsController',
            'boeking' => $boeking,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/book", name="book")
     */
    public function book(Request $request)
    {
        // get the cart from  the session
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        // $cart = $session->set('cart', '');
        $boeking = $session->get('boeking', array());

        if (!empty($boeking['tafels'])) {
            $tafels = [];
            foreach ($boeking['tafels'] as $key => $personen) {
                $tafels[$personen . ' personen '] = $key;
                //array_push($tafels, $key.' -> '.$personen. ' personen ');
            }
            //dump($tafels);
            $form = $this->createFormBuilder($boeking)
                ->add('aantal', IntegerType::class)
                ->add('datumtijd', DateTimeType::class)
                // ->add('tafels', CollectionType::class)
                ->add('tafels', ChoiceType::class, [
                    'choices' => $tafels,
                    'multiple' => true,
                ])
                ->add('save', SubmitType::class, array('label' => 'Maak Boeking'))
                ->getForm();
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $boeking = $form->getData();
            dump($boeking);
            // afhandelen van reservatie en in dbase plaatsen.
            $em = $this->getDoctrine()->getManager();
            //$repository = $this->getDoctrine()->getRepository(Reservatie::class);
            // opslaan
            $reservatie = new Reservatie();
            $reservatie->setAantal($boeking['aantal']);
            $reservatie->setDatumTijd($boeking['datumtijd']);
            $reservatie->setMedewerker($this->getUser());
            $reservatie->setUser($this->getUser());  // dit zou nog een invoer veld kunnen worden.
            foreach ($boeking['tafels'] as $tfl) {
                $tafel = $this->getDoctrine()->getRepository('App:Tafel')->findOneBy(['id' => ($tfl+1)]);
                $reservatie->addTafel($tafel);
            }
            $em->persist($reservatie);
            $em->flush();

            $session->remove('boeking');
            return $this->redirectToRoute('boeken');
        }

        return $this->render('boekings/index.html.twig', array(
            'controller_name' => 'BoekingsController',
            'boeking' => $boeking,
            'form' => $form->createView(),
        ));
    }
}