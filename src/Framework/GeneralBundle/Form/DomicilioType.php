<?php

namespace Framework\GeneralBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Framework\GeneralBundle\Form\Type\ComboPartidoType;
use Framework\GeneralBundle\Form\DireccionType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DomicilioType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options){
		//var_dump($options);
		unset($options['class']);
		
		if(isset($options['attr']['tipo'])) {$tipo = $options['attr']['tipo']; }
		else $tipo = '1';
		
		$builder
			->add('dirGeneral', new SelectorLocalidadType(), $options )
//			->add('latitud')
//			->add('longitud')
			->add('tipo', 'hidden',array('data'=>'1'))
			->add('dirRural', new DireccionRuralType())
			->add('dirUrbano', new DireccionUrbanoType())				
			->add('dirIndustrial', new DireccionIndustrialType())				
			;
	}

	public function getName(){
		return 'domiciliotype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		
			 $resolver->setDefaults(array('attr' => array('nombre' => 'nombrePorDefault')
                             ));
	}
	
}
