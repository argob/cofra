<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RetencionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\RetencionTipo'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_retenciontipotype';
    }
}
