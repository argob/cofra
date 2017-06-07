<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Magyp\RendicionDeCajaBundle\EventListener\ComprobanteFormularioSubscriber;

class ComprobanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		//var_dump($options['data']->getRendicion()->getId());
        $nroRendicion = $options['data']->getRendicion() ? $options['data']->getRendicion()->getId() : 0 ;
	
	//para ver 2.2+ no va factory.
	//Habilitar cuando se necesite usar.
	//$subscriber = new ComprobanteFormularioSubscriber($builder->getFormFactory());
	
        $builder
            ->add('fecha') 
            ->add('tipofactura',null, array('empty_value' => false,'attr' => array('style' => 'width:208px')))
            ->add('numero','text',array('required'=> false, 'max_length' => 4, 'attr' => array('size' => '4','class' => 'numero-parte1') ))
            ->add('numero2','text',array('required'=> false, 'max_length' => 8, 'attr' => array('size' => '8','class' => 'numero-parte2')))
            ->add('descripcion',null, array('attr' => array('style' => 'width:200px')))
            ->add('importe',null, array('attr' => array('style' => 'width:200px')))
            ->add('rendicion','hidden',array('data' => $nroRendicion))	
            ->add('proveedor',null,array('invalid_message' => 'Campo vacio no permitido', 'attr' => array('style' => 'width:208px'),'query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_buscarProveedores();}))
            ->add('imputacion',null,array('invalid_message' => 'Campo vacio no permitido', 'attr' => array('style' => 'width:208px'),'query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_buscarImputaciones();}))
	    //->addEventSubscriber($subscriber)
        ;
	
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Comprobante'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_comprobantetype';
    }
}
