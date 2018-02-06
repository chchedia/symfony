<?php
namespace OC\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use OC\PlatformBundle\Email\ApplicationMailer;
use OC\PlatformBundle\Entity\Application;

class ApplicationCreationListener
{
    /**
     * @var ApplicationMailer
     */
    private $applicationMailer;

    /**
     * ApplicationCreationListener constructor.
     * @param ApplicationMailer $applicationMailer
     */

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer=$applicationMailer;
    }

    /**
     * send notification if the entity is Application
     * @param LifecycleEventArgs $args
     */

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity=$args->getObject();

        if(!$entity instanceof Application)
        {
            return;
        }
        $this->applicationMailer->sendNewNotification($entity);
    }
}
?>