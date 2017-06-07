<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// src/Acme/DemoBundle/Validator/constraints/ContainsAlphanumeric.php

namespace Framework\GeneralBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VDomicilioRural extends Constraint {

	public $message = 'The string "%string%" contains an illegal character: it can only contain letters or numbers.';

	
}

?>
