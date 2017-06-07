<?php
namespace Magyp\MensajeBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Magyp\MensajeBundle\Controller\MensajeSession;

class MensajeInternoSubscriber implements EventSubscriberInterface
{
    const DISABLED        = 1;
    const ENABLED         = 2;

    protected $templating;
    protected $interceptRedirects;
    protected $mode;
    protected $position;
    protected $mensajesession;

    public function __construct(TwigEngine $templating, $interceptRedirects = false, $mode = self::ENABLED, $position = 'bottom')
    {
        $this->templating = $templating;
        $this->interceptRedirects = (Boolean) $interceptRedirects;
        $this->mode = (integer) $mode;
        $this->position = $position;
    }


    public function onKernelResponse(FilterResponseEvent $event, $em)
    {
	// $em 
	//se busca mensaje internos para el usuario actual.
	//De: para:  Ruta, template, condicion, expiro, leido, borrado.
	if($this->mensajesession->hasMensaje())$this->injectarMensaje($response);
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
        $pos = $posrFunction($content, '</body>');

        if (false !== $pos) {
            $toolbar = "\n".str_replace("\n", '', $this->templating->render(
                'MagypMensajeBundle:Mensaje:mensaje.inject.twig',
                array(
                    'position' => $this->position,
                    'token' => $response->headers->get('X-Debug-Token'),
		    'mensaje' => $this->mensajesession->getMensaje()
                )
            ))."\n";
            $content = $substrFunction($content, 0, $pos).$toolbar.$substrFunction($content, $pos);
            $response->setContent($content);
        }
    }


    public static function getSubscribedEvents()
    {
	
        return array(
//            KernelEvents::RESPONSE => array('onKernelResponse', 0), // prioridad 0
//	    'cofra.mensajeflashTWIG' => array('onMensajeFlash', 0),	    
	    );
    }

    
    public static function logensession($request, $variable)
    {
	$session = $request->getSession();
        $log = $session->get('Log');
	$session->set('Log',$log.'//'.$variable);
    }
    public static function limpiarlogensession($request )
    {
	$session = $request->getSession();
	$session->set('Log','');
    }

    
}
