<?php

namespace Framework\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PermisosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("permisosContenidos",
					"entity",
					array(
						'class'=>  'FrameworkSeguridadBundle:PermisoComposite',
						'multiple' => true,
						'expanded' => true
					)
				)

		;
	}

	public function getName() {
		return 'framework_seguridadbundle_permisostype';
	}

}
