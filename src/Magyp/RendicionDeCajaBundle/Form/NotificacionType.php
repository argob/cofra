<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotificacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenido',"textarea",array('attr' => array('style' => 'width:280px; height:80px')))
            ->add('asunto',null,array('attr' => array('style' => 'width:280px;')))
            ->add('destino','entity', array('invalid_message' => 'Campo vacio no permitido', 'class' => 'MagypRendicionDeCajaBundle:Area', 'attr' => array('style' => 'width:283px'),'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {  /* echo get_class($er); die(); */ return $er->qb_destino();}))
	//	->add('proveedor',null,array('attr' => array('style' => 'width:350px'),'query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_buscarProveedores();}))
       //      ->add('fecha')
            ->add('link',null,array('attr' => array('style' => 'width:264px;')))
          //  ->add('motivoTexto','textarea',array("label"=>"Motivo",'attr' => array('style' => 'width:280px; height:80px')))
	    
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Notificacion'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_notificaciontype';
    }
}
