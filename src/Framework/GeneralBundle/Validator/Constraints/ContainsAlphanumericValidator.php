<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
// src/Acme/DemoBundle/Validator/Constraints/ContainsAlphanumericValidator.php
namespace Framework\GeneralBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^[a-zA-Za0-9]+$/', $value, $matches)) {
            $this->setMessage($constraint->message, array('%string%' => $value));

            return false;
        }

        return true;
    }
}
?>
