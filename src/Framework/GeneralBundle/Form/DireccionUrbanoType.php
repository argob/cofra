<?php

namespace Framework\GeneralBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Framework\GeneralBundle\Form\Type\ComboPartidoType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionUrbanoType extends AbstractType {

	public function buildForm(FormBuilder $builder, array $options) {

		$builder
				->add('calle',null,array( 'required'    => false))
				->add('numero',null,array( 'required'    => false))
				->add('piso', 'text',array( 'required'    => false))					
				->add('dpto', 'text',array( 'required'    => false));
	}

	public function getName() {
		return 'dirUrbano';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
	 $resolver->setDefaults(array(
			'data_class' => 'Framework\GeneralBundle\Entity\Direccion\DireccionUrbano',
		));
	}

}

?>
