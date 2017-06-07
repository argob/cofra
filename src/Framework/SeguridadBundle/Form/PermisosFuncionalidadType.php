<?php

namespace Framework\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PermisosFuncionalidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("permisosContenidos",
					"entity",
					array(
                                                'class'=>  'FrameworkSeguridadBundle:Funcionalidad',
						'multiple' => true,
						'expanded' => true
					)
				)

		;
	}

	public function getName() {
		return 'framework_seguridadbundle_permisosfuncionalidadtype';
	}

}
