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

class MensajeTwigCofraSubscriber implements EventSubscriberInterface
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
    public function isEnabled()
    {
        return self::DISABLED !== $this->mode;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
	//echo '<br>me lanzo. kernel.response MensajeTwigcofra';
	//var_dump($this->templating);
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
	//echo '  [--]llege 000'; 
        $response = $event->getResponse();
        $request = $event->getRequest();
	
        // do not capture redirects or modify XML HTTP Requests
        if ($request->isXmlHttpRequest()) {
            return;
        }
//	$variable = $request->server->get('REDIRECT_STATUS').'Response:'.$response->getStatusCode();
//	$this->logensession($request, $variable);
	if ($response->getStatusCode() == '302') return; // si esta en medio de un redirect no injectar.
	//$session = $request->getSession();
	//$this->mensaje = $session->get('mensaje');
	$this->mensajesession = new MensajeSession($request);
	
	
	//$this->limpiarlogensession($request);
//	
//	    echo '<pre>';
//	    echo $request->getSession()->get('Log');
//	    var_dump($request);
//	    echo '</pre>';
//	    die();
	

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

    public function onMensajeFlash(){	
	echo 'mensajeflash TWIG Executado<br><br>';
	
    }
   
    public static function getSubscribedEvents()
    {
	
        return array(
            KernelEvents::RESPONSE => array('onKernelResponse', 0), // prioridad 0
	    'cofra.mensajeflashTWIG' => array('onMensajeFlash', 0),	    
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
