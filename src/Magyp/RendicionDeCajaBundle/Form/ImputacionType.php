<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImputacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('descripcion')
            ->add('tipo', "entity", array(
				'class' => 'MagypRendicionDeCajaBundle:ImputacionTipo',
				'property' => 'nombre',
				'label' => 'Tipo',
                ))
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Imputacion'
        ));
    }

    public function getName()
    {
        return 'imputacion';
    }
}
