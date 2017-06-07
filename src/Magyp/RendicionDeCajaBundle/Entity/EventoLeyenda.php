<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoLeyenda
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoLeyendaRepository")
 */
class EventoLeyenda extends Evento
{

    /**
     * @var Magyp\RendicionDeCajaBundle\Entity\Leyenda $leyenda
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Leyenda")
     * @ORM\JoinColumn(name="leyenda_id",referencedColumnName="id")
     */
    private $leyenda;

    /**
     * Set leyenda
     *
     * @param Magyp\RendicionDeCajaBundle\Entity\Leyenda $leyenda
     * @return EventoLeyenda
     */
    public function setLeyenda($leyenda)
    {
        $this->leyenda = $leyenda;
    
        return $this;
    }

    /**
     * Get leyenda
     *
     * @return string Magyp\RendicionDeCajaBundle\Entity\Leyenda
     */
    public function getLeyenda()
    {
        return $this->leyenda;
    }
    
    function __construct($usuario, $tipoEvento, $leyenda, $leyendaAnterior = null, $request = null) {
	$this->setUsuario($usuario);
	$this->setFecha(new \DateTime());
	$this->setLeyenda($leyenda);
	$this->setTipoEvento($tipoEvento);
	if(!is_null($request))$this->request = $request;

	if($tipoEvento == self::MODIFICIACION){
		$modifaciones = $this->BuscarCambios($leyendaAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
		if(count($modifaciones)){ // si hay modifiaciones
			$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
		}
		$this->mensajeActualizado();
	}
	$this->mensaje();

	}	

	function BuscarCambios(Leyenda $leyendaAnterior){
	    $item = array();	    
	    if(strcmp($leyendaAnterior->getLeyenda(), $this->getLeyenda()->getLeyenda())){		//si hay cambios logue esto.
		    $item[] = new LogRegistroDato($this, 'leyenda', $leyendaAnterior->getLeyenda(), $this->getLeyenda()->getLeyenda());
	    }
	    if(strcmp($leyendaAnterior->getAnio()->format("Y"), $this->getLeyenda()->getAnio()->format("Y"))){	//si hay cambios logue esto.
		    $item[] = new LogRegistroDato($this, 'año', $leyendaAnterior->getAnio()->format("Y"), $this->getLeyenda()->getAnio()->format("Y"));
	    }
	    if($leyendaAnterior->getAsignado() != $this->getLeyenda()->getAsignado()){
		    $item[] = new LogRegistroDato($this, 'asignado', $leyendaAnterior->getAsignado() ? "Asignado" : "No Asignado", $this->getLeyenda()->getAsignado() ? "Asignado" : "No Asignado" );
	    }
	    return $item;
	}
	public function mensaje(){	
	  //  \Doctrine\Common\Util\Debug::dump($this->request);
		if(is_null($this->request)) return; // evito q explote otras partes q no usan mensajes.
		$tipoEvento = $this->getTipoEvento();
		if($tipoEvento == self::NUEVO)$this->mensajeNuevo("Accion", "Se ha creado una leyenda nueva");
		if($tipoEvento == self::BORRAR)$this->mensajeBorrar("Accion", "Se Borro la Leyenda con exito");
		if($tipoEvento == self::RESTAURAR)$this->mensajeRestaurar("Accion", "Se restauro la Leyenda");
		if($tipoEvento == self::MODIFICIACION)$this->mensajeActualizado("Accion", "Se actualizaron los datos de la Leyenda");
			
	}

}
