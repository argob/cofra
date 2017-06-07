<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReintegroDeGastoDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('importesubtotal')
            ->add('reintegrodegasto')
            ->add('programa')
            ->add('imputacion')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGastoDetalle'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_reintegrodegastodetalletype';
    }
}
