<?php

namespace Framework\GeneralBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class milabelType extends AbstractType {

	public function buildForm(FormBuilder $builder, array $options) {
		$builder
				->add('texto')
		;
	}

	public function getParent(array $options) {
		return 'field';
	}

	public function getName() {
		return 'milabel';
	}

}
