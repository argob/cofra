<?php

namespace Magyp\RendicionDeCajaBundle\Kernel;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Magyp\MensajeBundle\Controller\MensajeSession;
use Magyp\RendicionDeCajaBundle\Entity\Notificacion;
use Magyp\RendicionDeCajaBundle\Entity;

class Cofra
{
    CONST BUNDLE = "MagypRendicionDeCajaBundle";
    private $em;
    private $controller;
    private $securityContext;
    private $request;
    
    function __construct(SecurityContext $securityContext, Request $request, Doctrine $doctrine) {
	$this->securityContext = $securityContext;
	$this->em = $doctrine->getManager();
	$this->request = $request;	
    }

     /**************************************************************************************************************
     * Seccion Funciones y Metodos Internos de la clase.
     ***************************************************************************************************************/
    
    /** 
     * Get Request
     * 
     * @return Request
     */
    
    private function getRequest() {
	return $this->request;
    }
    
    /**	
     * Get Usuario
     * 
     * @return Entity\Usuario
     */
    
    private function getUser(){
        if (null === $token = $this->securityContext->getToken()) {
            return null;
        }
        if (!is_object($user = $token->getUser())) {
            return null;
        }
        return $user;
    }

    /**
     * Setea el Controlador con el cual va a trabajar la clase.
     * 
     * @param Controller $controller
     * @return \Magyp\RendicionDeCajaBundle\Kernel\Cofra
     */
    public function setController($controller) {
	$this->controller = $controller;
	return $this;
    }
    
    public function flush() {
	$this->em->flush();
    }
    
     /**************************************************************************************************************
     * Seccion Entidades y Repositorios. 
     * Obtiene las entidades manejadas por el sistema. 
     * Obtiene los repositorios asociados a las entidadades
     * Uso obtener Entidad: get{Nombre de la entidad}($id)
     * Uso obtener Repositorio get{Nombre de la entidad en plural}()
     ***************************************************************************************************************/
    
    /**
     * Get rendicion
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Rendicion
     */
    
    public function getRendicion($idRendicion) {
	return $this->em->getRepository(self::BUNDLE. ":Rendicion")->find($idRendicion);	
    }    
    /**
     * Get rendiciones
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\RendicionRepository
     */
    
    public function getRendiciones() {
	return $this->em->getRepository(self::BUNDLE. ":Rendicion");
    }
    
    /**
     * Get comprobante
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Comprobante
     */
    
    public function getComprobante($idComprobante) {
	return $this->em->getRepository(self::BUNDLE. ":Comprobante")->find($idComprobante);	
    }
    
    /**
     * Get comprobante
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\ComprobanteRepository
     */
    
    public function getComprobantes() {
	return $this->em->getRepository(self::BUNDLE. ":Comprobante");

    }

    /**
     * Get Proveedor
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Proveedor
     */
    public function getProveedor($idProveedor) {
	return $this->em->getRepository(self::BUNDLE. ":Proveedor")->find($idProveedor);
	
    }
    
    /**
     * Get Proveedores
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\ProveedorRepository
     */
    public function getProveedores() {
	return $this->em->getRepository(self::BUNDLE. ":Proveedor");	
    }
    
    /**
     * Get Imputacion
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Imputacion
     */    
    
    public function getImputacion($idImputacion) {
	return $this->em->getRepository(self::BUNDLE. ":Imputacion")->find($idImputacion);	
    }
    
    /**
     * Get Imputaciones
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\ImputacionRepository
     */    
    
    public function getImputaciones() {
	return $this->em->getRepository(self::BUNDLE. ":Imputacion");	
    }
    
    /**
     * Get Usuario
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario
     */        
    
    public function getUsuario($idUsuario) {
	return $this->em->getRepository(self::BUNDLE. ":Usuario")->find($idUsuario);	
    }
    
    /**
     * Get Usuarios
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\UsuarioRepository
     */        
    
    public function getUsuarios() {
	return $this->em->getRepository(self::BUNDLE. ":Usuario");	
    }
    
    /**
     * Get Area
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Area
     */
    
    public function getArea($idArea) {
	return $this->em->getRepository(self::BUNDLE. ":Area")->find($idArea);
	
    }
    
    /**
     * Get Areas
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\AreaRepository
     */
    
    public function getAreas() {
	return $this->em->getRepository(self::BUNDLE. ":Area");	
    }
    
     /**************************************************************************************************************
     * Seccion Manejo de eventos. 
     * Agregar y Obtener Eventos. 
     * uso: getEvento{Nombre de la entidad}($id)
     *	    addEvento{Nombre de la entidad}({Evento::Nuevo, Evento::Borrar, Evento::Modificado, etc}, {Entidad}, [Entidad Anterior])
     ***************************************************************************************************************/
    
    /**
     * Agrega EventosComprobantes.
     * 
     * @param integer $tipoEvento
     * @param Entity\Comprobante $comprobante
     * @param Entity\Comprobante $comprobanteAnterior
     */
    
    public function addEventoComprobante( $tipoEvento, $comprobante, $comprobanteAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoComprobante = new EventoComprobante($usuario, $tipoEvento, $comprobante, $comprobanteAnterior, $this->getRequest());
		$this->em->persist($eventoComprobante);
		// los flush deben ir todos juntos $em->flush();
    }   
   	  
    
    /**
     * Get EventoComprobante
     * @param integer $id
     * @return Entity\EventoComprobante
     */
    
    public function getEventoComprobante($idComprobante) {
	return $this->em->getRepository(self::BUNDLE. ":EventoComprobante")->findByComprobante($idComprobante);	
    }
    
    /**
     * Agrega EventosImputaciones.
     * 
     * @param integer $tipoEvento
     * @param Entity\Proveedor $imputacion
     * @param Entity\Proveedor $imputacionAnterior
     */
    
    public function addEventoImputacion( $tipoEvento, $imputacion, $imputacionAnterior = null){
        
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$this->em->refresh($usuario);
        $eventoImputacion = new Entity\EventoImputacion($usuario, $tipoEvento, $imputacion, $imputacionAnterior, $this->getRequest());
		$this->em->persist($eventoImputacion);
		// los flush deben ir todos juntos $em->flush();
    }   
   		
    /**
     * Get EventoImputacion
     * @param integer $id
     * @return Entity\EventoImputacion
     */
        
    public function getEventoImputacion($idImputacion) {
	return $this->em->getRepository(self::BUNDLE. ":EventoImputacion")->findByImputacion($idImputacion);	
    }
    
    /**
     * Agrega EventosProveedores.
     * 
     * @param integer $tipoEvento
     * @param Entity\Proveedor $proveedor
     * @param Entity\Proveedor $proveedorAnterior
     */
    
    public function addEventoProveedor( $tipoEvento, $proveedor, $proveedorAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoProveedor = new Entity\EventoProveedor($usuario, $tipoEvento, $proveedor, $proveedorAnterior, $this->getRequest());
		$this->em->persist($eventoProveedor);
		// los flush deben ir todos juntos $em->flush();
    }   
   	
    /**
     * Get EventoProveedor
     * @param integer $id
     * @return Entity\EventoProveedor
     */
        
    public function getEventoProveedor($idProveedor) {
	return $this->em->getRepository(self::BUNDLE. ":EventoProveedor")->findByProveedor($idProveedor);	
    }
    
    /**
     * Agrega un EventoRendicion
     *
     * @return Cofra
     */
    
    public function addEventoRendicion($tipoEvento, $rendicion, $rendicionAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoRendicion = new Entity\EventoRendicion($usuario, $tipoEvento, $rendicion, $rendicionAnterior, $this->getRequest());
		$this->em->persist($eventoRendicion);
	return $this;
    }	
    /**
     * Get EventoRendicion
     * @param integer $id
     * @return Entity\EventoRendicion
     */
        
    public function getEventoRendicion($idRendicion) {
	return $this->em->getRepository(self::BUNDLE. ":EventoRendicion")->findByRendicion($idRendicion);	
    }
    
    /**
     * Agrega EventosUsuario.
     * 
     * @param integer $tipoEvento
     * @param Entity\Usuario $usuariodestino
     * @param Entity\Usuario $usuariodestinoAnterior
     */
    
    public function addEventoUsuario( $tipoEvento, $usuariodestino, $usuariodestinoAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoUsuario = new Entity\EventoUsuario($usuario, $tipoEvento, $usuariodestino, $usuariodestinoAnterior, $this->getRequest());
		$this->em->persist($eventoUsuario);
		// los flush deben ir todos juntos $em->flush();
    }   	
    /**
     * Get EventoUsuario
     * @param integer $id
     * @return Entity\EventoUsuario
     */
            
    public function getEventoUsuario($idUsuario) {
	return $this->em->getRepository(self::BUNDLE. ":EventoUsuario")->findByUsuario($idUsuario);	
    }    
  
    /**
     * Agrega un EventoLeyenda
     *
     * @return Cofra
     */
    
    public function addEventoLeyenda($tipoEvento, $leyenda, $leyendaAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoLeyenda = new Entity\EventoLeyenda($usuario, $tipoEvento, $leyenda, $leyendaAnterior, $this->getRequest());
		$this->em->persist($eventoLeyenda);
	return $this;
    }
    
    /**
     * Get EventoLeyenda
     * 
     * Info: Obtiene los eventos de la Leyenda correspondiente al $id enviado.
     * @param integer $id
     * @return Entity\EventoLeyenda
     */
    
    public function getEventoLeyenda($idLeyenda) {
		return $this->em->getRepository(self::BUNDLE. ":EventoLeyenda")->findByLeyenda($idLeyenda);	
    }  

    /**
     * Agrega un EventoLiquidacion
     *
     * @return Cofra
     */
    
    public function addEventoLiquidacion($tipoEvento, $liquidacion, $liquidacionAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoLiquidacion = new Entity\EventoLiquidacion($usuario, $tipoEvento, $liquidacion, $liquidacionAnterior, $this->getRequest());
		$this->em->persist($eventoLiquidacion);
	return $this;
    }

    /**
     * Get EventoLiquidacion
     * 
     * Info: Obtiene los eventos de la Liquidacion correspondiente al $id enviado.
     * @param integer $id
     * @return Entity\EventoLiquidacion
     */
    
    public function getEventoLiquidacion($idLiquidacion) {
		return $this->em->getRepository(self::BUNDLE. ":EventoLiquidacion")->findByLiquidacion($idLiquidacion);	
    }      

    /**
     * Agrega un EventoArea
     *
     * @return Cofra
     */
    
    public function addEventoArea($tipoEvento, $area, $areaAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoArea = new Entity\EventoArea($usuario, $tipoEvento, $area, $areaAnterior, $this->getRequest());
		$this->em->persist($eventoArea);
	return $this;
    }

    /**
     * Get EventoArea
     * 
     * Info: Obtiene los eventos del Area correspondiente al $id enviado.
     * @param integer $id
     * @return Entity\EventoArea
     */
    
    public function getEventoArea($idArea) {
		return $this->em->getRepository(self::BUNDLE. ":EventoArea")->findByArea($idArea);	
    }      
    

    /**
     * Agrega un EventoArchivar
     *
     * @return Cofra
     */
    
    public function addEventoArchivar($tipoEvento, $archivar, $areaAnterior = null){
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$eventoArchivar = new Entity\EventoArchivar($usuario, $tipoEvento, $archivar, $areaAnterior, $this->getRequest());
		$this->em->persist($eventoArchivar);
		return $this;
    }

    /**
     * Get EventoArchivar
     * 
     * Info: Obtiene los eventos del Archivar correspondiente al $id enviado.
     * @param integer $id
     * @return Entity\EventoArchivar
     */
    
    public function getEventoArchivar($idArchivar) {
		return $this->em->getRepository(self::BUNDLE. ":EventoArchivar")->findByArchivar($idArchivar);	
    }      
    
     /**************************************************************************************************************
     * Seccion Mensajes Web y Notificaciones Internas.
     * Agrega Mensajes Web de aviso al usuario Y notificaciones internas entre las Areas del sistema
     * 
     ***************************************************************************************************************/

    /**
     * Crea una Notificacion para un Area en especifico.
     * 
     * @param string $contenido
     * @param string $asunto
     * @param Entity\Area $destino
     * @param string $link
     * @return \Magyp\RendicionDeCajaBundle\Kernel\Cofra
     */
    
    public function crearNotificacion($contenido,$asunto,$destino,$link = null) {
		$usuario = $this->em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUser()->getId());
		$notificacion = new Notificacion($usuario);
		$notificacion->setAsunto($asunto);
		//$rand = "  ".strval(rand(1, 100000));
		//$notificacion->setContenido($contenido. $rand);	
		$notificacion->setContenido($contenido);	
		$notificacion->setDestino($destino);	
		$notificacion->setLink($link);	
		$this->em->persist($notificacion);
		//var_dump($notificacion);
		return $this;
    }

    public function crearMensaje(){
    }
    
    /**
     * Crea mensaje de Error en la pagina
     * 
     * @param string $titulo
     * @param string $texto
     * @return \Magyp\RendicionDeCajaBundle\Kernel\Cofra
     */
    
    public function crearMensajedeError($titulo,$texto){
	$ms = new MensajeSession($this->getRequest());
	$ms->setMensaje($titulo,$texto)
		->Error()
		->Generar();	
	return $this;
    }
    
    /**
     * Crea mensaje de Exito y lo visualiza en la pagina.
     * 
     * @param string $titulo
     * @param string $texto
     * @return \Magyp\RendicionDeCajaBundle\Kernel\Cofra
     */    
    public function crearMensajedeExito($titulo,$texto){
	$ms = new MensajeSession($this->getRequest());
	$ms->setMensaje($titulo,$texto)
		->Exito()
		->Generar();
	return $this;
    }
    
    /**
     * Renderiza una pantalla de error.
     * 
     * @param controller $controller
     * @param string $mensaje
     * @return TwigResponse
     */
    public function PantallaError($mensaje) {	
	return $this->controller->render("MagypRendicionDeCajaBundle:Pantalla:error.html.twig",array("mensaje" => $mensaje));
    }
    
     /**************************************************************************************************************
     * Seccion: Herramientas de Desarrollo
     * 
     ***************************************************************************************************************/
    
    /**
     * Dumpea el contenido de/las variable/s
     * 
     * @param Cualquier $variable, +[$variable2]
     */
    public function dump($variable, $maxDepth = 4 ){
	//$maxDepth = 4;
	if (extension_loaded('xdebug')) {
	    ini_set('xdebug.var_display_max_depth', $maxDepth);
	}
	for($n = 0; $n<func_num_args();$n++){
	    $variable = func_get_arg($n);
	    $info = is_object($variable) ? get_class($variable) : gettype($variable);
	    echo "--------------------------------------------  ".  $info ."  --------------------------------------------";		
	    var_dump($variable);
	}
    }    
}
