<?php

namespace OC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction (Request $request)
    {
        //si user est déjà connecté
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHRNTICATED_REMEMBRED'))
        {
            return $this->redirectToRoute('oc_platform_accueil');
        }
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('OCUserBundle:Security:login.html.twig', array(
            'last_username'=> $authenticationUtils->getLastUsername(),
            'error'=> $authenticationUtils->getLastAuthenticationError(),
        ));
    }
}