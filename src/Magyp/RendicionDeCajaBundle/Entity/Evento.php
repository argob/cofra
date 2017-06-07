<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Evento
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="tipo",type="integer")
 * @ORM\DiscriminatorMap({"1"="EventoComprobante","2"="EventoImputacion","3"="EventoProveedor","4"="EventoRendicion","100"="EventoUsuario",
 *				"5"="EventoLeyenda", "6"="EventoLiquidacion", "7"="EventoArea", "8"="EventoArchivar", "9"="EventoNotificacion"})

 */
abstract class Evento
{	
	const NUEVO = 1;
	const MODIFICIACION = 2;
	const BORRAR = 3;
	const RESTAURAR = 4;
	const TIEMPORESTAURACION = 10; // es en minutos, no puede superar los 60.
	const LOGIN = 100;
	const CAMBIOPASSWORD = 101;
	const CAMBIODEESTADO = 5;
	const NOTIFICACIONLEIDA = 200;
    const BAJA = 300;
    const NUEVOPORBAJA = 301;
    
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $usuario
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id",referencedColumnName="id")
     */
    private $usuario;

    /**
     * @var integer $tipoEvento
     *
     * @ORM\Column(name="tipoEvento", type="integer")
     */
    private $tipoEvento;	

	/**
     * @var \Magyp\RendicionDeCajaBundle\Entity\LogRegistroDato[] $listaDeCambiosEscalares
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\LogRegistroDato", mappedBy="evento", cascade={"persist"})
     */
    private $listaDeCambiosEscalares;	
	
	/**
     * @var \Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad[] $listaDeCambiosEntidad
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad", mappedBy="evento", cascade={"persist"})
     */
    private $listaDeCambiosEntidad;	
	
    protected $request;
    
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Evento
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set abm
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Abm $abm
     * @return Evento
     */
    public function setAbm($abm)
    {
        $this->abm = $abm;
    
        return $this;
    }

    /**
     * Get abm
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Abm
     */
    public function getAbm()
    {
        return $this->abm;
    }

    /**
     * Set usuario
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Usuario $usuario
     * @return Evento
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

	function __toString() {
		return (string)$this->getId();
	}

	public function getTipoEvento() {
		return $this->tipoEvento;
	}

	public function setTipoEvento($tipoEvento) {
		$this->tipoEvento = $tipoEvento;
	}

	public function getListaDeCambios() {
		$listaDeCambios = array();		
		if($this->listaDeCambiosEscalares instanceof \Doctrine\ORM\PersistentCollection)$this->listaDeCambiosEscalares = $this->listaDeCambiosEscalares->toArray();
		if($this->listaDeCambiosEntidad instanceof \Doctrine\ORM\PersistentCollection)$this->listaDeCambiosEntidad = $this->listaDeCambiosEntidad->toArray();
		if(is_array($this->listaDeCambiosEscalares))foreach( $this->listaDeCambiosEscalares as $item){$listaDeCambios[] = $item;}
		if(is_array($this->listaDeCambiosEntidad))foreach( $this->listaDeCambiosEntidad as $item){$listaDeCambios[] = $item;}
		
		//var_dump($this->listaDeCambiosEscalares);
		//var_dump($this->listaDeCambiosEntidad);
		
		return $listaDeCambios;
	}
	
	// guarda cada item en su lista
	public function setListaDeCambios($listaDeCambios) {
		foreach( $listaDeCambios as $item){
			if($item instanceof LogRegistroDato){
				$this->listaDeCambiosEscalares[] = $item;
			}
			if($item instanceof LogRegistroEntidad){
				$this->listaDeCambiosEntidad[] = $item;
			}
		}
		
	}

	public function getNombreTipoEvento() {
		$nombres = array(
				self::NUEVO => "Nuevo",
				self::MODIFICIACION => "Modificado",
				self::BORRAR => "Borrado",
				self::RESTAURAR => "Restaurado",
				self::CAMBIODEESTADO => "Cambio de Estado",
                self::NOTIFICACIONLEIDA => "Notificacion Leida",
                self::BAJA => "Baja",
                self::NUEVOPORBAJA => "Nuevo por Caja Cerrada"
		);
		return $nombres[$this->getTipoEvento()];
	}

	/**
	 *  Para Metodos para reescribirlos en las clases hijas
	 */
	public function mensajeNuevo($titulo = 'Accion',$texto = "Se creo con exito")
	{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
		$mensaje->setMensaje($titulo,$texto)
		->Nuevo()
		->Generar();	
	}
	public function mensajeActualizado($titulo = 'Accion',$texto = "Se Actualizaron los datos")
	{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
		$mensaje->setMensaje($titulo,$texto)
		->Exito()		    
		->Generar();	

	}
	public function mensajeBorrar($titulo = 'Accion',$texto = "Se borro con exito")
	{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
		$mensaje->setMensaje($titulo,$texto)
		->Exito()
		->Generar();		    	    
	}
	public function mensajeRestaurar($titulo = 'Accion',$texto = "Se restauro con exito")
	{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
		$mensaje->setMensaje($titulo,$texto)
		->Exito()
		->Generar();		    	    
	}
    public function mensajeBaja($titulo = 'Accion',$texto = "Se dio de baja con exito")
	{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
		$mensaje->setMensaje($titulo,$texto)
		->Exito()
		->Generar();		    	    
	}

}
