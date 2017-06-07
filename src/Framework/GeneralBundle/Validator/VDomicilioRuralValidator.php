<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Framework\GeneralBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VDomicilioRuralValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
		//$km = $value
		if(strlen($km) >5){
			$this->setMessage($constraint->message, array('%string%' => $value));
			return false;
			
		}
		
		return true;
		
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->setMessage($constraint->message, array('%string%' => $value));

            return false;
        }

		
				
        return true;
    }
}
?>
