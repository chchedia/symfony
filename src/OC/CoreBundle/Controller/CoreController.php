<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CoreController extends Controller
{
    //la page d'accueil
    public function indexAction()
    {
        return $this->render("OCCoreBundle:Core:index.html.twig");
    }

    //la page contact
    public function contactAction(Request $request){
        $session= $request->getSession();

        $session->getFlashBag()->add('info','La page Contact n\'est pas encore disponible');

        return $this->redirectToRoute('oc_core_homepage');
    }

}