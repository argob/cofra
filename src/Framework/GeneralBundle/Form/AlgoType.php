<?php

namespace Framework\GeneralBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AlgoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
				->add('a')
				->add('b')
				->add('c')
			
        ;
    }

    public function getName()
    {
        return 'algodealgo';
    }
}
