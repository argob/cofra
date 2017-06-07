<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\EventoRendicion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoRendicionRepository")
 */
class EventoRendicion extends Evento
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion")
     * @ORM\JoinColumn(name="rendicion_id",referencedColumnName="id")
     */
    private $rendicion;
    
    protected $request;

//    /**
//     * @var \stdClass $evento
//     *
//     * @ORM\Column(name="evento", type="object")
//     */
//    private $evento;


    /**
     * Set rendicion
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @return EventoRendicion
     */
    public function setRendicion($rendicion)
    {
        $this->rendicion = $rendicion;
    
        return $this;
    }

    /**
     * Get rendicion
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Rendicion 
     */
    public function getRendicion()
    {
        return $this->rendicion;
    }

	function __construct($usuario, $tipoEvento, $rendicion, $rendicionAnterior = null, $request = null) {
		$this->setUsuario($usuario);
		$this->setFecha(new \DateTime());
		$this->setRendicion($rendicion);
		$this->setTipoEvento($tipoEvento);
		$this->request = $request;
		//$this->mensaje();	// mensajes aca
		if($tipoEvento == self::MODIFICIACION || $tipoEvento == self::CAMBIODEESTADO  ){
			$modifaciones = $this->BuscarCambios($rendicionAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
			//var_dump($modifaciones);
			if(count($modifaciones)){ // si hay modifiaciones
				$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
			}
		}
	}

	function BuscarCambios($rendicionAnterior){
		$item = array();
		if(strcmp($rendicionAnterior->getExpediente(), $this->getRendicion()->getExpediente())){		//si hay cambios logue esto.
			$item[] = new LogRegistroDato($this, 'Expediente', $rendicionAnterior->getExpediente(), $this->getRendicion()->getExpediente());
		}
		if($rendicionAnterior->getEstado() != $this->getRendicion()->getEstado()){
			$item[] = new LogRegistroDato($this, "Estado", $rendicionAnterior->getEstadoNombre(), $this->getRendicion()->getEstadoNombre());
		}		
		return $item;
	}
}
