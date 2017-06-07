<?php

// src/Acme/DemoBundle/Form/Type/GenderType.php

namespace Framework\GeneralBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Framework\GeneralBundle\Form\Type\ComboPartidoType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SelectorLocalidadType extends AbstractType {

    public function buildForm(FormBuilder $builder, array $options) {
        if (!isset($options['attr']['nombre']))
            $options['attr']['nombre'] = "";

        $funcionProvincia = 'pedirprov_form_' . $options['attr']['nombre'] . '_' . $builder->getName() . '()';
        //@todo: cambiar nombre pedirprov_ a algo que realmente haga
        $funcionPartido = 'pedirpart_form_' . $options['attr']['nombre'] . '_' . $builder->getName() . '()';
        $funcionLocalidad = 'pedirloc_form_' . $options['attr']['nombre'] . '_' . $builder->getName() . '()';
        $funcionCodPostal = 'pedircodpostal_form_' . $options['attr']['nombre'] . '_' . $builder->getName() . '()';
        $funcionAgregaCP = 'agregacp_form_' . $options['attr']['nombre'] . '_' . $builder->getName() . '()';
        $partidoDefault = array(7 => 'CIUDAD AUTONOMA BUENOS AIRES');
        $localidadDefault = array(3058 => 'CIUDAD AUTONOMA BUENOS AIRES');

        if (!isset($options['attr']['localidad'])) {
            $builder->add('provincia', "entity", array(
                'class' => 'FrameworkGeneralBundle:Provincia',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Provincias',
                'property_path' => false,
                'attr' => array('style' => 'width: 300px;'), //'attr' => array('onchange' => $funcionPartido, 'onclick' => $funcionProvincia)
                'preferred_choices' => array('1'),
            ));
            $builder->add('partido', 'choice', array(
                'choices' => $partidoDefault,
                'property_path' => false,
                'attr' => array('style' => 'width: 300px;'),
            ));
            $builder->add('localidad', 'choice', array(
                'choices' => $localidadDefault,
                'attr' => array('style' => 'width: 300px;')//'attr' => array( 'onchange' => $funcionCodPostal)
            ));
//			$builder->add('codpostal', 'choice',array( 
//						'class'=>  'FrameworkGeneralBundle:Localidad',
//						'property_path' => false,
//						'attr' => array('style' => 'width: 300px;'),//'attr' => array( 'onchange' => $funcionAgregaCP)
//						'query_builder' => function (\Framework\GeneralBundle\Entity\LocalidadRepository $repository)  {
//										return $repository->buscarCodigosPostalQB(3058);
//						}
//						)); // sugerencia q se agrega al input

            $builder->add('codigopostal'); // el q lee el formulario
        } else {
            $localidad = $options['attr']['localidad'];
            $partido = $localidad->getPartido();
            $provincia = $partido->getProvincia();


            $selectLocalidad = $builder->create('localidad', "entity", array(
                'class' => 'FrameworkGeneralBundle:Localidad',
                //	'choices'=> $options['attr']['localidades'],
                'label' => 'Localidad',
                'attr' => array('style' => 'width: 300px;'),
                'preferred_choices' => array($localidad->getId()),
                'query_builder' => function (\Framework\GeneralBundle\Entity\LocalidadRepository $repository) use ($partido) {
            return $repository->buscarDePartidoQB($partido->getId());
//										return $repository->createQueryBuilder('l')
//												->where("l.partido = :partidoid")
//												->setParameter('partidoid',378);
        }
                    )
            );
            $selectPartido = $builder->create('partido', "entity", array(
                'class' => 'FrameworkGeneralBundle:Partido',
                //'choices'=> $options['attr']['partidos'],
                'label' => 'Partido',
                'attr' => array('style' => 'width: 300px;'),
                'preferred_choices' => array($localidad->getPartido()->getId()),
                'query_builder' => function (\Framework\GeneralBundle\Entity\PartidoRepository $repository) use ($provincia) {
            return $repository->buscarPorProvinciaQB($provincia->getId());
        }
                    )
            );
            $selectProvincia = $builder->create('provincia', "entity", array(
                'class' => 'FrameworkGeneralBundle:Provincia',
                'label' => 'Provincias',
                'property_path' => false,
                'attr' => array('style' => 'width: 300px;'), //'attr' => array('onchange' => $funcionPartido, 'onclick' => $funcionProvincia)						
                'preferred_choices' => array($localidad->getPartido()->getProvincia()->getId()),
            ));

//			$selectcodpostal = $builder->create('codpostal', 'choice',array( 
//						'property_path' => false,
//						'choices' => $localidad->getCodpostales(),
//						'attr' => array('style' => 'width: 300px;'),
//
//						)); // sugerencia q se agrega al input


            $builder->add($selectProvincia);
            $builder->add($selectPartido);
            $builder->add($selectLocalidad);
            //	$builder->add($selectcodpostal);
            $builder->add('codigopostal'); // el q lee el formulario
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults($resolver);
    }

    public function getParent(array $options) {
        return 'form';
    }

    public function getName() {
        return 'selectorlocalidad';
    }

}

/**
  $builder->add('users', 'entity', array(
  'class' => 'AcmeHelloBundle:User',
  'query_builder' => function(EntityRepository $er) {
  return $er->createQueryBuilder('u')
  ->orderBy('u.username', 'ASC');
  },



  if(!isset($options['attr']['partidos']))
  $builder->add('partido',"entity",array(
  'class'=>  'FrameworkGeneralBundle:Partido',
  'choices'=> $options['attr']['partidos'],
  'multiple' => false,
  'expanded' => false,
  'label' => 'Partido',
  'attr' => array('style' => 'width: 300px;'),

  )
  );

  if(!isset($options['attr']['localidades']))
  $builder->add('localidad',"entity",array(
  'class'=>  'FrameworkGeneralBundle:Partido',
  'choices'=> $options['attr']['localidades'],
  'multiple' => false,
  'expanded' => false,
  'label' => 'Localidad',
  'attr' => array('style' => 'width: 300px;')
  )
  );
  if(!isset($options['attr']['codpostales']))
  $builder->add('codpostal',"choice",array(
  'choices'=> $options['attr']['codpostales'],
  'attr' => array('style' => 'width: 300px;'),//'attr' => array( 'onchange' => $funcionAgregaCP)
  )
  );
 * */
?>


