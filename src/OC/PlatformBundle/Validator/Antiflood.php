<?php
//srv/OC/PlatformBundle/Validator/Antiflood.php

namespace OC\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class Antiflood extends Constraint
{
    public $message="Vous avez déjà posté un messsage il y amois de 15 secondes, merci d'attendre un peu";
}