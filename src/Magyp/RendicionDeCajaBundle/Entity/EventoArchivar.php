<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Magyp\RendicionDeCajaBundle\Entity\Archivar;

/**
 * EventoArchivar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoArchivarRepository")
 */
class EventoArchivar extends Evento
{

    /**
     * @var Magyp\RendicionDeCajaBundle\Entity\Archivar $archivar
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Archivar")
     * @ORM\JoinColumn(name="archivar_id",referencedColumnName="id")
     */
    private $archivar;

	public function getArchivar() {
		return $this->archivar;
	}

	public function setArchivar($archivar) {
		$this->archivar = $archivar;
	}

	    
    function __construct($usuario, $tipoEvento, $archivar, $archivarAnterior = null, $request = null) {
	$this->setUsuario($usuario);
	$this->setFecha(new \DateTime());
	//var_dump($archivar->getCaja());
	//var_dump($archivarAnterior->getCaja());
	$this->setArchivar($archivar);
	
	$this->setTipoEvento($tipoEvento);
	if(!is_null($request))$this->request = $request;

	if($tipoEvento == self::MODIFICIACION ){	   
		$modifaciones = $this->BuscarCambios($archivarAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
		if(count($modifaciones)){ // si hay modifiaciones
			$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
		}
		$this->mensajeActualizado();
	}
	$this->mensaje();

	}	

	function BuscarCambios(Archivar $archivarAnterior){
	    $item = array();	
		
	var_dump($archivarAnterior->getCaja());
	var_dump($this->getArchivar()->getCaja());
	    if($archivarAnterior->getCaja() != $this->getArchivar()->getCaja()){		//si hay cambios logue esto.
		    $item[] = new LogRegistroDato($this, 'Caja', $archivarAnterior->getCaja(), $this->getArchivar()->getCaja());
	    }
	    if($archivarAnterior->getRendicion()->getExpedienteCompleto()!= $this->getArchivar()->getRendicion()->getExpedienteCompleto()){	
		    $item[] = new LogRegistroDato($this, 'Rendicion', $archivarAnterior->getMonto(), $this->getArchivar()->getMonto());
	    }

	    if($archivarAnterior->getLiquidacion()!= $this->getArchivar()->getLiquidacion()){			
			$item[] = new LogRegistroEntidad($this,"Liquidacion",LogRegistroEntidad::ARCHIVAR, $archivarAnterior->getActividad() );
		}	    
	    return $item;
	}
	public function mensaje(){	
	  //  \Doctrine\Common\Util\Debug::dump($this->request);
		if(is_null($this->request)) return; // evito q explote otras partes q no usan mensajes.
		$tipoEvento = $this->getTipoEvento();
		if($tipoEvento == self::NUEVO)$this->mensajeNuevo("Accion", "Se ha Archivado con exito");
		//if($tipoEvento == self::BORRAR)$this->mensajeBorrar("Accion", "Se Borro la Area con exito");
		//if($tipoEvento == self::RESTAURAR)$this->mensajeRestaurar("Accion", "Se restauro la Area");
		if($tipoEvento == self::MODIFICIACION)$this->mensajeActualizado("Accion", "Se actualizaron los datos con exito");
			
	}

}
