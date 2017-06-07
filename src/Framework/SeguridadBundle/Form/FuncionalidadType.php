<?php

namespace Framework\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FuncionalidadType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('nombre')
				->add('permisosContenidos')
		;
	}

	public function getName() {
		return 'framework_seguridadbundle_funcionalidadtype';
	}

}
