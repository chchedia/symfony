<?php

namespace OC\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 *
 * @ORM\Table(name="oc_user")
 * @ORM\Entity(repositoryClass="OC\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\Advert", mappedBy="user")
     */
    private $adverts;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->adverts= new ArrayCollection();
        parent::__construct();
    }


}
