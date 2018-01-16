<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{

    public function indexAction()
    {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig',array('nom' => 'Chedia'));
        return new Response($content);
    }

    public function byebyeAction()
    {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:byebye.html.twig',array('nom' => 'Chedia'));
        return new Response($content);
    }
}