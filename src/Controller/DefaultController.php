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
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
                'role' => 'ADMIN',
            ]);
        } elseif ($this->security->isGranted('ROLE_USER')) {
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
                'role' => 'USER',
            ]);
        } else {
            return $this->render('default/index.html.twig', [
                'controller_name' => 'DefaultController',
                'role' => 'NONE',
            ]);

        }
    }
}
