<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CoreController extends Controller
{

    public function indexAction()
    {
        return $this->render("OCCoreBundle:Core:index.html.twig");
    }


}