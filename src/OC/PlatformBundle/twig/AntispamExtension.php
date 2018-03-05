<?php

// src/OC/PlatformBundle/twig/AntispamExtension.php

namespace OC\PlatformBundle\twig;

use OC\PlatformBundle\Antispam\OCAntispam;

class AntispamExtension extends \Twig_Extension
{
    /**
     * @var OCAntispam
     */
    private $ocAntispam;

    /**
     * AntispamExtension constructor.
     * @param OCAntispam $ocAntispam
     */
    public function __construct(OCAntispam $ocAntispam)
    {
        $this->ocAntispam = $ocAntispam;
    }

    /**
     * @param $text
     * @return bool
     */
    public function checkIfArgumentIsSpam($text)
    {
        return $this->ocAntispam->isSpam($text);
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('checkIfSpam', array($this, 'checkIfArgumentIsSpam')),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'OCAntispam';
    }
}