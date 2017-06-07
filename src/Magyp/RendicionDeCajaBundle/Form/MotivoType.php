<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MotivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('contenido')
            ->add('contenido',"textarea",array('label'=> "Motivo",'attr' => array('style' => 'width:395px; height:160px')))
         //   ->add('notificacion')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Motivo'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_motivotype';
    }
}
