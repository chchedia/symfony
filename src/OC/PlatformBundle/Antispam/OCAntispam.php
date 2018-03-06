<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace OC\PlatformBundle\Antispam;

class OCAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    /**
     * OCAntispam constructor.
     * @param \Swift_Mailer $mailer
     * @param $locale
     * @param $minLength
     */
    public function __construct(\Swift_Mailer $mailer, $minLength)
    {
        $this->mailer    = $mailer;
        $this->minLength = (int) $minLength;
    }

    /**
     * @param $locale
     */
    public function setLocale($locale)
    {
        $this->locale= $locale;
    }
    /**
     * Vérifie si le texte est un spam ou non
     *
     * @param string $text
     * @return bool
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}