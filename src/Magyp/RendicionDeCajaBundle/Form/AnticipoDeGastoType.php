<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnticipoDeGastoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expediente')
            ->add('nota')
            ->add('area',null,array('query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_destino();}))
            ->add('monto')
            ->add('motivo','textarea')   
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\AnticipoDeGasto'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_anticipodegastotype';
    }
}
