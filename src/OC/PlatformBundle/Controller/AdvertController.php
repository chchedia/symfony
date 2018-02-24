<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }
        $nbPerPage = 3;

        // récupérer la liste des annonces
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->getAdverts($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        //la vue index
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . "n'existe pas.");
        }

        // On récupère la liste des candidatures de cette annonce
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert));

        $listAdvetSkills = $em->getRepository("OCPlatformBundle:AdvertSkill")->findBy(array("advert" => $advert));
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvetSkills
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $advert=new Advert();

        $form =$this->createForm(AdvertType::class, $advert);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //$advert->getImage()->upload();
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bine enregistrée.');

            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('form' => $form->createView()));

    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }
        //la gestion du formulaire

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }


    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        // Ici, on récupérera l'annonce correspondant à $id
        $advert = $em->getRepository("OCPlatformBundle:Advert")->find($id);

        if ($advert === null) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas!");
        }
        // boucler les catégories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        //declancher la modification
        $em->flush();

        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        $limitedList = $em->getRepository("OCPlatformBundle:Advert")->findBy(
            array(),
            array('date' => 'desc'),
            $limit,
            0
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $limitedList
        ));
    }

    public function purgeAction($days, Request $request)
    {
        $purger = $this->get("oc_platform.purger.advert");
        $purger->purge($days);

        $request->getSession()->getFlashBag()->add('Alerte', 'Les anciens annonces, depuis ' . $days . ' jours ont été purgées.');
        return $this->redirectToRoute('oc_platform_home');
    }
}