<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Response;

class HomeController extends BaseCofraController
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
		
		return $this->redirect($this->generateUrl('home'));
		//return $this->redirect($this->generateUrl('login'));
		return array();

        
    }
     /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
	
	return array();
	
        
    }
     /**
     * @Route("/sistema/session", name="session")
     * 
     */
    public function sessionAction()
    {
	
//		$backup = session_id();
//		session_destroy();
//		session_id("3epfguclh3v9ro2pcc6djoi2g2");
//		session_start();
//		session_destroy();
//		
//		session_id($backup);
//		session_start();		
		
		$usuario =$this->getUsuario();
		$em = $this->getDoctrine()->getManager();
		$ultimasession = $em->getRepository("MagypRendicionDeCajaBundle:Session")->ultimaSession($usuario);
		$session = $this->getRequest()->getSession();
		$agentedeusuario = $_SERVER['HTTP_USER_AGENT'];
		//$this->getCofra()->dump($agentedeusuario);
		//$this->getCofra()->dump(md5($agentedeusuario));
		$this->getCofra()->dump($session->getId());
		$this->getCofra()->dump($ultimasession);
		
		$this->getCofra()->dump($session);
		$this->getCofra()->dump($usuario);
		return new Response();
	
        
    }	
     /**
     * @Route("/sistema/session/borrar/{hash}", name="session_borrar_hash")
     * 
     */
    public function sessionBorrarHASHAction($hash)
    {
		session_id($hash);	
		session_destroy();
		return new Response('destruida: '.$hash);
        
	}
     /**
     * @Route("/sistema/session/borrar", name="session_borrar")
     * 
     */
    public function sessionBorrarAction()
    {
	
		$backup = session_id();
//		session_destroy();
		session_id('b9def5clld5vdc8frrt6hd7pn7');		
		
		session_destroy();
	//	session_start();
		
		session_id($backup);
		session_start();		
		
		$session = $this->getRequest()->getSession();
		$usuario =$this->getUsuario();
		$this->getCofra()->dump($session->getId());
		$this->getCofra()->dump($session);
		$this->getCofra()->dump($usuario);
		return new Response();
	
        
    }
}
