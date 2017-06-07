<?php
namespace Magyp\RendicionDeCajaBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
use Magyp\RendicionDeCajaBundle\Entity\EventoUsuario;
use Magyp\RendicionDeCajaBundle\Entity\Evento;

class LoginListener {

    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

	private $request;
	
    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine, \Symfony\Component\HttpFoundation\Request $request) {
	$this->securityContext = $securityContext;
	$this->em = $doctrine->getManager();
	$this->request = $request;
    }

    /**
     * 
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {

		//var_dump($this->securityContext);
		//die;
		
	if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
	    $usuario = $event->getAuthenticationToken()->getUser();
	    if ( $usuario instanceof \Magyp\RendicionDeCajaBundle\Entity\Usuario){
			
			
		$evento = new EventoUsuario($usuario,Evento::LOGIN,null,null,$event->getRequest());
		$session = new \Magyp\RendicionDeCajaBundle\Entity\Session($usuario,$event->getRequest());
		//$session->BorrarSessionesAnteriores($usuario,$this->em);
		$this->em->persist($session);	    
		$this->em->persist($evento);	    
		$this->em->flush($evento);

		$mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($event->getRequest());
		$mensaje->setMensaje('Bienvenido','Iniciando sistema Cofra.')		    
		->Exito()
		->setIcono('inicio1.png')		    
		->Generar();
        $bAreaBorrada= $usuario->getArea()->getBorrado();
        if ( $bAreaBorrada == 1 ){
            $this->securityContext->setToken(null);
            $this->request->getSession()->invalidate();
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($event->getRequest());
			$mensaje->setMensaje('Logueo','Su caja asignada ah sido dada de baja.')->Error()->Generar();	
            return true;
        }
        
//		var_dump($usuario->getErrorLogueo());
//		var_dump($usuario);
//		die();
		return false;
	    }
	    
	}
	if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
	    // usuario se ha logueado usado recordar_me de cookie
	    
	}
	    // do some other magic here
	$user = $event->getAuthenticationToken()->getUser();
	// ...
    }
//    public function onErrorLogin(\Symfony\Component\Security\Core\Event\AuthenticationEvent $event) {
//	
//		//var_dump($event);		
//		echo "-----";
//		var_dump($event->getAuthenticationToken()->getUser());		
//		var_dump($event->getAuthenticationToken());		
//		var_dump($this->request);		
//		echo "-----";
////		die;
//	//	var_dump($this->securityContext);
//		$nombredeusuario=  $event->getAuthenticationToken()->getUser();
////		$mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($event->getRequest());
////		$mensaje->setMensaje('Bienvenido','asdasdasdasd.')		    
////		->Exito()
////		->setIcono('inicio1.png')		    
////		->Generar();
//		$usuario = $this->em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername($nombredeusuario);
//		if(!empty($usuario)){
//			//echo 'el usuario existe';
//			//var_dump($usuario);
//			$usuario->IncrementaErrorLogueo();
//			if($usuario->SuperaErroresPermitidos() == true) $this->request->getSession()->set("cuentabloqueada", "true");
//			if($usuario->SuperaErroresPermitidos() == true) $this->request->getSession()->set("cuentabloqueada", "true");
//			if($usuario->AvisarAlMail() == true) $this->request->getSession()->set("avisarpormail", $usuario->getId());
//			
//			$this->em->persist($usuario);
//			$this->em->flush();
//		}
//	//	die();
//	
//		
//	}
//
//    public function onAuthenticationEvent(\Symfony\Component\Security\Core\Event\AuthenticationEvent $event) {
//		die('evneto auth');
//		
//	}	
}