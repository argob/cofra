<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
                    ->add('username', null, array(
                            'label' => 'Nombre de usuario',
                            'max_length' => 255
                    ))

                    ->add('dni', null, array(
                            'max_length' => 8
                    ))

                    ->add('email', 'repeated', array(
                            "type" => "email",
//					"first_name" => "email",
//					"second_name" => "Confirmar email",
                            "invalid_message" => "Su email no concuerda",
                            'first_options'  => array('label' => 'e-mail'),
                            'second_options' => array('label' => 'Confirmar e-mail'),
			   // 'error_bubbling' => true
                            
                    ))

//				->add('captcha', 'captcha', array(
//					"invalid_message" => "Captcha incorrecto"
//				))
                    ->add('nombre', null, array(
                            'max_length' => 80
                    ))

                    ->add('apellido', null, array(
                            'max_length' => 80
                    ))
                    ->add('area', "entity", array(
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_areas();},
                    'class' => 'MagypRendicionDeCajaBundle:Area',
                    'property' => 'nombre',
                    'label' => 'Area',
                    ))

//                    ->add('programa', "entity", array(
//                                    'class' => 'MagypRendicionDeCajaBundle:Programa',
//                                    'property' => 'programa_id',
//                                    'label' => 'Programa',
//                    ))
//
//                    ->add('actividad', "entity", array(
//                                    'class' => 'MagypRendicionDeCajaBundle:Actividad',
//                                    'property' => 'actividad_id',
//                                    'label' => 'Actividad',
//                    ))

                    ->add('activo', 'checkbox', array(
                    'label'     => 'activo',
                    'required'  => false,
                    ))

                    ->add('bloqueada', 'checkbox', array(
                    'label'     => 'bloqueada',
                    'required'  => false,
                    ))
                    ;
		;
	}

	public function getName() {
		return 'registro';
	}

}


