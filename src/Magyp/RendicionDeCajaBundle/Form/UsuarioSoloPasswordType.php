<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioSoloPasswordType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
                    ->add('password', 'repeated', array(
                            "type" => "password",
                            
                            //"first_name" => "Clave",
                            //"second_name" => "Confirmar clave",
                            "invalid_message" => "La clave no concuerda",
                            'first_options'  => array("attr" => array('class' => 'passowrd_btn'),'label' => 'Password'),
                            'second_options' => array("attr" => array('class' => 'passowrd_btn'),'label' => 'Confirmar Password')
                    ))
                    ;
		;
	}

	public function getName() {
		return 'registro_solo_password';
	}

}


