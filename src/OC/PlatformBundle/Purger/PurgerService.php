<?php
// src/OC/PlatformBundle/Purger/PurgerService.php

namespace OC\PlatformBundle\Purger;

use Doctrine\ORM\EntityManagerInterface;

class PurgerService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function purge($days)
    {

        $advertRepo = $this->em->getRepository("OCPlatformBundle:Advert");
        $advertSkillRepo = $this->em->getRepository("OCPlatformBundle:AdvertSkill");

        $date = new \DateTime($days.'days');

        $listAdvert = $advertRepo->getAdvertBefore($date);

        foreach ($listAdvert as $advert) {
            $listAdvertSkills = $advertSkillRepo->findBy(array('advert' => $advert));

            foreach ($listAdvertSkills as $advertSkill) {
                $this->em->remove($advertSkill);
            }

            $this->em->remove($advert);
        }
        $this->em->flush();
    }
}