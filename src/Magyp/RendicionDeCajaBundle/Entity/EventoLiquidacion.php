<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoLiquidacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoLiquidacionRepository")
 */
class EventoLiquidacion extends Evento
{

    /**
     * @var Magyp\RendicionDeCajaBundle\Entity\Liquidacion $liquidacion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Liquidacion")
     * @ORM\JoinColumn(name="liquidacion_id",referencedColumnName="id")
     */
    private $liquidacion;

    /**
     * Set liquidacion
     *
     * @param Magyp\RendicionDeCajaBundle\Entity\Liquidacion $liquidacion
     * @return EventoLiquidacion
     */
    public function setLiquidacion($liquidacion)
    {
        $this->liquidacion = $liquidacion;
    
        return $this;
    }

    /**
     * Get liquidacion
     *
     * @return string Magyp\RendicionDeCajaBundle\Entity\Liquidacion
     */
    public function getLiquidacion()
    {
        return $this->liquidacion;
    }
    
    function __construct($usuario, $tipoEvento, $liquidacion, $liquidacionAnterior = null, $request = null) {
	$this->setUsuario($usuario);
	$this->setFecha(new \DateTime());
	$this->setLiquidacion($liquidacion);
	$this->setTipoEvento($tipoEvento);
	if(!is_null($request))$this->request = $request;

	if($tipoEvento == self::MODIFICIACION){
		$modifaciones = $this->BuscarCambios($liquidacionAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
		if(count($modifaciones)){ // si hay modifiaciones
			$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
		}
		$this->mensajeActualizado();
	}
	$this->mensaje();

	}	

	function BuscarCambios(Liquidacion $liquidacionAnterior){
	    $item = array();	
	    
	    if(strcmp($liquidacionAnterior->getNota(), $this->getLiquidacion()->getNota())){		//si hay cambios logue esto.
		    if(!is_null($liquidacionAnterior->getNota())){
			$item[] = new LogRegistroDato($this, 'nota', $liquidacionAnterior->getNota(), $this->getLiquidacion()->getNota());
		    }
	    }
	    if( $liquidacionAnterior->getFecha() == $this->getLiquidacion()->getFecha()){	
		if(!is_null($liquidacionAnterior->getFecha())){
		    $item[] = new LogRegistroDato($this, 'fecha', $liquidacionAnterior->getFecha(), $this->getLiquidacion()->getFecha());
		}
	    }
	    return $item;
	     
	     
	}
	public function mensaje(){	
	  //  \Doctrine\Common\Util\Debug::dump($this->request);
		if(is_null($this->request)) return; // evito q explote otras partes q no usan mensajes.
		$tipoEvento = $this->getTipoEvento();
		if($tipoEvento == self::NUEVO)$this->mensajeNuevo("Accion", "Se ha creado una liquidacion nueva");
		if($tipoEvento == self::BORRAR)$this->mensajeBorrar("Accion", "Se Borro la Liquidacion con exito");
		if($tipoEvento == self::RESTAURAR)$this->mensajeRestaurar("Accion", "Se restauro la Liquidacion");
		if($tipoEvento == self::MODIFICIACION)$this->mensajeActualizado("Accion", "Se actualizaron los datos de la Liquidacion");
			
	}

}
