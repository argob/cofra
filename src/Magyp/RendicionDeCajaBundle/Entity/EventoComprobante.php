<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\EventoComprobante
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoComprobanteRepository")
 */
class EventoComprobante extends Evento
{
//    /**
//     * @var integer $id
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Comprobante $comprobante
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Comprobante")
     * @ORM\JoinColumn(name="comprobante_id",referencedColumnName="id")
     */
    private $comprobante;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion")
     * @ORM\JoinColumn(name="rendicion_id",referencedColumnName="id")
     */
    private $rendicion;	
        	
	
//    /**
//     * @var \stdClass $evento
//     *
//     * @ORM\Column(name="evento", type="object")
//     */
//    private $evento;


    /**
     * Set comprobante
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Comprobante $comprobante
     * @return EventoComprobante
     */
    public function setComprobante($comprobante)
    {
        $this->comprobante = $comprobante;
    
        return $this;
    }

    /**
     * Get comprobante
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Comprobante 
     */
    public function getComprobante()
    {
        return $this->comprobante;
    }

	function __construct($usuario, $tipoEvento, $comprobante, $comprobanteAnterior = null, $request = null) {
		$this->setUsuario($usuario);
		$this->setFecha(new \DateTime());
		$this->setComprobante($comprobante);
		$this->setTipoEvento($tipoEvento);
		$this->setRendicion($comprobante->getRendicion());
		$this->request = $request;
		$this->mensaje();	// mensajes aca
		if($tipoEvento == self::MODIFICIACION){

			//echo '<br>es modificacion';
			$modifaciones = $this->BuscarCambios($comprobanteAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
			//var_dump($modifaciones);
			if(count($modifaciones)){ // si hay modifiaciones
				$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
			}
		}
		
	}


	function BuscarCambios($comprobanteAnterior){
		$item = array();
		if(strcmp($comprobanteAnterior->getDescripcion(), $this->getComprobante()->getDescripcion())){		//si hay cambios logue esto.
			$item[] = new LogRegistroDato($this, 'Descripcion', $comprobanteAnterior->getDescripcion(), $this->getComprobante()->getDescripcion());
		}
		if($comprobanteAnterior->getImporte() != $this->getComprobante()->getImporte()){
		//	echo '<br> cambios en Importe';
			$item[] = new LogRegistroDato($this, "Importe", $comprobanteAnterior->getImporte(), $this->getComprobante()->getImporte());
		}
		if($comprobanteAnterior->getNumero()!= $this->getComprobante()->getNumero()){
			$item[] = new LogRegistroDato($this, "Numero", $comprobanteAnterior->getNumero(), $this->getComprobante()->getNumero());
		}
//		if($comprobanteAnterior->getNumeroFoja()!= $this->getComprobante()->getNumeroFoja()){
//			$item[] = new LogRegistroDato($this, "NumeroFoja", $comprobanteAnterior->getNumeroFoja(), $this->getComprobante()->getNumeroFoja());
//		}
		if($comprobanteAnterior->getProveedor()!= $this->getComprobante()->getProveedor()){			
			$item[] = new LogRegistroEntidad($this,"Proveedor",LogRegistroEntidad::PROVEEDOR, $comprobanteAnterior->getProveedor() );
		}
		if($comprobanteAnterior->getImputacion()!= $this->getComprobante()->getImputacion()){			
			$item[] = new LogRegistroEntidad($this,"Imputacion",LogRegistroEntidad::IMPUTACION, $comprobanteAnterior->getImputacion() );
		}
		return $item; // array vacio si no hay cambios, array con los items de cada cambio si hay modificaciones.
	}

	public function getRendicion() {
		return $this->rendicion;
	}

	public function setRendicion($rendicion) {
		$this->rendicion = $rendicion;
	}

	public function esRestaurable(){		
		$intervalo =  date_diff($this->getFecha() , new \DateTime(), true);		
		$tiempo= $intervalo->y + $intervalo->m +$intervalo->d + $intervalo->h*60 + $intervalo->i;		
		if ($intervalo->i < Evento::TIEMPORESTAURACION) return true;  // si pasaron menos de 10 minutos
		else return false;
		
	}
	

			
	public function mensaje(){	
	
		if(is_null($this->request)) return; // evito q explote otras partes q no usan mensajes.
		$tipoEvento = $this->getTipoEvento();
		if($tipoEvento == self::NUEVO)$this->mensajeNuevo('Accion','Nuevo Comprobante Nº ' .$this->getComprobante()->getNumero());
		if($tipoEvento == self::BORRAR)$this->mensajeBorrar('Accion','Se ha borrado el Comprobante ' .$this->getComprobante()->getNumero());
		if($tipoEvento == self::RESTAURAR)$this->mensajeRestaurar('Accion','Se ha restaurado el Comprobante ' .$this->getComprobante()->getNumero());
		if($tipoEvento == self::MODIFICIACION)$this->mensajeActualizado('Accion','Se actualizo el Comprobante ' .$this->getComprobante()->getNumero());;
			
	}


}
