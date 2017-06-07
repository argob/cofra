<?php

namespace Framework\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('username')
				->add('password', "password")
				->add('email', 'email')
		;
	}

	public function getName() {
		return 'framework_seguridadbundle_usuariotype';
	}

}
