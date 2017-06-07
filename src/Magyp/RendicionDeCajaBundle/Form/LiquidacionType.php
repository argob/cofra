<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LiquidacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expediente')
            ->add('nota')
            ->add('periodoinicial')
            ->add('periodofinal')
            ->add('fuentefinanciamiento')
            ->add('ug')
            ->add('beneficiario')
            ->add('rendicion')
            ->add('area')
            ->add('responsable')
            ->add('actividad')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Liquidacion'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_liquidaciontype';
    }
}
