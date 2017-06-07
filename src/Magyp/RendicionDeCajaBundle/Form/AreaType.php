<?php

namespace Magyp\RendicionDeCajaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('responsable',null,array('empty_value' =>'NO TIENE', 'query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_responsables_disponibles();}))
            ->add('subresponsable',null,array('empty_value' =>'NO TIENE', 'query_builder' => function(\Doctrine\ORM\EntityRepository $er) { return $er->qb_responsables_disponibles();}))
            ->add('monto')
            ->add('programa', "entity", array(
                'class' => 'MagypRendicionDeCajaBundle:Programa',
                'property' => 'programa_id',
                'label' => 'Programa',
            ))

            ->add('actividad', "entity", array(
                    'class' => 'MagypRendicionDeCajaBundle:Actividad',
                    'property' => 'actividad_id',
                    'label' => 'Actividad',
            ))	
             ->add('ff', "entity", array(
                    'class' => 'MagypRendicionDeCajaBundle:FuenteFinanciamiento',
                    'property' => 'ff_id',
                    'label' => 'Fuente de Financiemiento',
            ))	
            ->add('ug', "entity", array(
                    'class' => 'MagypRendicionDeCajaBundle:UbicacionGeografica',
                    'property' => 'numero',
                    'label' => 'UG',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magyp\RendicionDeCajaBundle\Entity\Area'
        ));
    }

    public function getName()
    {
        return 'magyp_rendiciondecajabundle_areatype';
    }
}
