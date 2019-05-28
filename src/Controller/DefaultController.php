<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $checkTime = new \DateTime("now");
            $checkTime->modify('- 10 minutes');

            $repository = $this->getDoctrine()
                ->getRepository('App:User');

            $query = $repository->createQueryBuilder('p')
                ->where('p.lastActivityAt > :checkTime')
                ->setParameter('checkTime', $checkTime)
                ->getQuery();

            $users = $query->getResult();

            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
                'role' => 'ADMIN',
                'users' => $users,
            ]);
        } elseif ($this->security->isGranted('ROLE_USER')) {
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
                'role' => 'USER',
                'users' => null
            ]);
        } else {
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
                'role' => 'NONE',
                'users' => null,
            ]);

        }
    }
}
