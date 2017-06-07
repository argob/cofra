<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion','text', array( 'label' => 'Razon Social', 'max_length' => 80, 'attr'=>array( 'class' => 'descripcion', 'max_length' => 80)))
            ->add('cuit','text', array(
                            'label' => 'Cuit',
                            'max_length' => 11) )
            //->add('Area')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Proveedor'
        ));
    }

    public function getName()
    {
        return 'proveedor';
    }
}
