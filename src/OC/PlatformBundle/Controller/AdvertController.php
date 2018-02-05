<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
// aprés le commit supprimer LoadSkill
    public function indexAction($page)
    {
        // ...

        // Notre liste d'annonce en dur
        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
        );

        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if ($advert=== null) {
            throw new NotFoundHttpException("L'annonce d'id ". $id. "n'existe pas.");
        }

        // On récupère la liste des candidatures de cette annonce
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert))
        ;

        $listAdvetSkills= $em->getRepository("OCPlatformBundle:AdvertSkill")->findBy(array("advert"=>$advert));
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'           => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' =>$listAdvetSkills
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $advert= new Advert();
        $advert->setTitle("test et encore des tests!!");
        $advert->setAuthor('encore Chedia');
        $advert->setContent("le contenu de l'annonce test");
        $advert->setEmail("chebichedia@gmail.com");

        $image= new Image();
        $image->setUrl('https://upload.wikimedia.org/wikipedia/commons/9/9c/Pm5544_ntsc-2-.png');
        $image->setAlt('test');
        $advert->setImage($image);

        // Création d'une première candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une deuxième candidature par exemple
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        //recupérer Entity manager
        $em= $this->getDoctrine()->getManager();

        //reccupérer les skills
        $listSkills= $em->getRepository("OCPlatformBundle:Skill")->findAll();
        foreach ($listSkills as $skill) {
            $advertSkill= new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel("Expert");
            $em->persist($advertSkill);
        }
        //persiste l'entité
        $em->persist($advert);
        $em->persist($application1);
        $em->persist($application2);
        //flush tout ce qui a été persisté avant
        $em->flush();
        // La gestion d'un formulaire est particulière, mais l'idée est la suivante :

        // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if ($request->isMethod('POST')) {
            // Ici, on s'occupera de la création et de la gestion du formulaire

            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('oc_platform_view', array('id' =>$advert->getId()));
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert'=> $advert));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $advert->setContent("Je cherche des développeurs motivés!");
        $advert->updateDate();
        $em->flush();
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }


    public function deleteAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        // Ici, on récupérera l'annonce correspondant à $id
        $advert= $em->getRepository("OCPlatformBundle:Advert")->find($id);

        if ($advert === null){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas!");
        }
        // boucler les catégories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category){
            $advert->removeCategory($category);
        }

        //declancher la modification
        $em->flush();

        // Ici, on gérera la suppression de l'annonce en question

        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }


}