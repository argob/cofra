<?php

namespace Magyp\MensajeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
    
    /**
     * @Route("/mensaje")
     * @Template()
     */
    public function inicioAction()
    {	
	
	$subscriber = new \Magyp\MensajeBundle\EventListener\MensajeCofraSubscriber(); //es suscriptor
	$event = new \Symfony\Component\EventDispatcher\Event();

	$a = $this->container->get('event_dispatcher');
	
	$a->addSubscriber($subscriber);	
	//$a->dispatch('cofra.mensajeflash', $event);
	
	//echo ($a->hasListeners('kernel.exception') == true ? 'stiene el evento registrado' : 'NO tiene el evento registrado');
	
        return new \Symfony\Component\HttpFoundation\Response();
    }
    
    /**
     * @Route("/otromensaje")
     * @Template()
     */
    public function otromensajeAction()
    {	
	echo '<br>Este es un mensaje aparte<br>';
        return new \Symfony\Component\HttpFoundation\Response();
    }    
}
