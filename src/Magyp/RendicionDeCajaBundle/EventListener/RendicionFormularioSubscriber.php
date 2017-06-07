<?php
namespace Magyp\RendicionDeCajaBundle\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RendicionFormularioSubscriber implements EventSubscriberInterface
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
	$expediente = $data['expediente'];
	
	//preg_match("",$expediente);
	//$expediente = str_pad($expediente, 7, '0', STR_PAD_LEFT );
	//echo $expediente;
	
	
	
	
    }
    public function preSetData(FormEvent $event)
    {
	
        $data = $event->getData();
        $form = $event->getForm();
	
	
	// data es null cuando se crear el formulario.
        if (null === $data) {
	    
            return;
        }
	if($data->getId() === null){ // esto es para cuando creamos el formulario pero en vez de pasarle null le pasamos un objeto nuevo.
	    //$data->setExpediente('ingrese el expediente');
	}
        // comprueba si el objeto es nuevo, si id es diferente de null
        if ($data->getId()) {
	    $data->setExpediente($data->getExpediente()); // se queda con los ultimos 12 digitos. Elimina los 4 primeros "S05:"
            //$data->setExpediente(substr($data->getExpediente(), -12, 12)); // se queda con los ultimos 12 digitos. Elimina los 4 primeros "S05:"
            //echo $data->getExpediente();
            if(!$data->esModificable()){ // si no es modificable deshabilito los controles
		
	    }
        }
    }
}


?>