<?php
namespace Magyp\MensajeBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;


class MensajeCofraSubscriber implements EventSubscriberInterface
{
    const DISABLED        = 1;
    const ENABLED         = 2;
    
    protected $mode;
    
    public function __construct()
    {
        $this->mode = self::ENABLED;
    }

    public function isEnabled()
    {
        return self::DISABLED !== $this->mode;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
	//echo '<br>me lanzo. kernel.response MensajeCofra';
	
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();
        $request = $event->getRequest();

        // do not capture redirects or modify XML HTTP Requests
        if ($request->isXmlHttpRequest()) {
            return;
        }

	//$this->injectarMensaje($response);
    }

    /**
     * Injects the web debug toolbar into the given Response.
     *
     * @param Response $response A Response instance
     */
    protected function injectarMensaje(Response $response)
    {
        if (function_exists('mb_stripos')) {
            $posrFunction   = 'mb_strripos';
            $substrFunction = 'mb_substr';
        } else {
            $posrFunction   = 'strripos';
            $substrFunction = 'substr';
        }

        $content = $response->getContent();
        $pos = $posrFunction($content, '</mensaje>');

        if (false !== $pos) {
            $toolbar = "\n".str_replace("\n", '', $this->templating->render(
                'MagypMensajeBundle:Mensaje:codigo_js.html.twig',
                array(
                    'position' => $this->position,
                    'token' => $response->headers->get('X-Debug-Token'),
                )
            ))."\n";
            $content = $substrFunction($content, 0, $pos).$toolbar.$substrFunction($content, $pos);
            $response->setContent($content);
        }
    }

    public function onMensajeFlash(){	
	echo 'mensajeflash Executado<br><br>';
	
    }
    public function onAlert(){	
	echo '<br>Alerta!! lanzada!!<br>';
	
    }
    public function onKernelResponseCofra(FilterResponseEvent $event){
	//echo 'kernel response cofra';
    }    
    public static function getSubscribedEvents()
    {
	
        return array(
            KernelEvents::RESPONSE => array('onKernelResponse', 0), // prioridad 0
	    'cofra.mensajeflash' => array('onMensajeFlash', 0),
	    'cofra.alerta' => array('onAlert', 0),
	    );
    }
    
}
