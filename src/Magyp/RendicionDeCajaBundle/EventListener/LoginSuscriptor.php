<?php
namespace Magyp\RendicionDeCajaBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
use Magyp\RendicionDeCajaBundle\Entity\EventoUsuario;
use Magyp\RendicionDeCajaBundle\Entity\Evento;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;

class LoginSuscriptor implements EventSubscriberInterface {

    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

	private $request;
	
	private $configuracion;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine, \Symfony\Component\HttpFoundation\Request $request, $configuracion) {
	$this->securityContext = $securityContext;
	$this->em = $doctrine->getManager();
	$this->request = $request;
	$this->configuracion = $configuracion;
	
    }

    public function onErrorLogin(\Symfony\Component\Security\Core\Event\AuthenticationEvent $event) {
	
		
		//die("entro onError");
		//var_dump($this->configuracion->getConfig());		
		//die;
		//var_dump($event);		
		
//		echo "-----";
//		var_dump($event->getAuthenticationToken()->getUser());		
//		var_dump($event->getAuthenticationToken());		
//		var_dump($this->request);		
//		echo "-----";
//		die;
	//	var_dump($this->securityContext);
		$nombredeusuario=  $event->getAuthenticationToken()->getUser();
//		$mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($event->getRequest());
//		$mensaje->setMensaje('Bienvenido','asdasdasdasd.')		    
//		->Exito()
//		->setIcono('inicio1.png')		    
//		->Generar();
		$usuario = $this->em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername($nombredeusuario);
		if(!empty($usuario)){
			//var_dump($usuario);
			$usuario->IncrementaErrorLogueo();
			
		//	if($usuario->SuperaErroresPermitidos() == true) $this->request->getSession()->set("cuentabloqueada", "true");
			if($usuario->AvisarPorMailBloqueoDeCuenta() == true){
				$this->request->getSession()->set("cuentabloqueada",$usuario->getId());
			}
			if($usuario->AvisarAlMailExcesosLogueo() == true) $this->request->getSession()->set("avisarpormail", $usuario->getId());
			
			$this->em->persist($usuario);
			$this->em->flush();
		}
	//	die();
	
		
	}

    public function onAuthenticationEvent(AuthenticationEvent $event) {
		die('evneto auth');
		
	}	
    
    public function onAuthenticationSucess(\Symfony\Component\Security\Core\Event\AuthenticationEvent $event) {
		die('evneto auth sucess');
		
	}	
    
	static public function getSubscribedEvents()
    {
		AuthenticationEvents::AUTHENTICATION_FAILURE;		
        return array(
            AuthenticationEvents::AUTHENTICATION_FAILURE => "onErrorLogin",
        //    AuthenticationEvents::AUTHENTICATION_SUCCESS => "onAuthenticationSucess",
			
			
            
        );
    }	
}