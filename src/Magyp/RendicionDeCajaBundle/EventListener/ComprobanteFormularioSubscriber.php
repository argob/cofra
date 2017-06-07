<?php
namespace Magyp\RendicionDeCajaBundle\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ComprobanteFormularioSubscriber implements EventSubscriberInterface
{
    private $factory;
				    //FormFactoryInterface 
    public function __construct($factory)
    {
        $this->factory = $factory;
    }
    
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData',
		     FormEvents::PRE_BIND => 'preBinData'
		
		);
    }
    
    public function preBinData(FormEvent $event){
	$data = $event->getData();
        $form = $event->getForm();
	
	
	
    }
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
	
	// data es null cuando se crear el formulario.
        if (null === $data) {
            return;
        }

        // comprueba si el objeto es nuevo
        if (!$data->getId()) {
	    // desactivado
            //$form->add($this->factory->createNamed('id','text'));
        }
    }
}


?>