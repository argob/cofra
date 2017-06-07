<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UbicacionGeograficaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_ubicaciongeograficatype';
    }
}
