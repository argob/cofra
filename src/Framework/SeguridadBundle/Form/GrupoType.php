<?php

namespace Framework\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GrupoType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('nombre')
				->add('contenidos')
		;
	}

	public function getName() {
		return 'framework_seguridadbundle_grupotype';
	}

}
