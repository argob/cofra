<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FondoRotatorioRetencionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('retenciontipo')
            ->add('fondorotatorio')
            ->add('monto')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioRetencion'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_fondorotatorioretenciontype';
    }
}
