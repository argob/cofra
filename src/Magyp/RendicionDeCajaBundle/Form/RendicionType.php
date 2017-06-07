<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Magyp\RendicionDeCajaBundle\EventListener\RendicionFormularioSubscriber;

class RendicionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$subscriber = new RendicionFormularioSubscriber($builder->getFormFactory());
        $builder
            ->add('fecha')
	    //->add('tipo','choice')	
		
            ->add('expediente','text',array('attr' => array('style' => 'width:auto')))
            //->add('totalRendicion')
	    ->addEventSubscriber($subscriber)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
			'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Rendicion'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_rendiciontype';
    }
}
