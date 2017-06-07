<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogRegistroEntidad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidadRepository") 
 */
class LogRegistroEntidad extends ModificacionItem
{
	CONST COMPROBANTE = 1;
	CONST IMPUTACION = 2;
	CONST PROVEEDOR = 3;
	CONST RENDICION = 4;
	CONST PROGRAMA = 5;
	CONST ACTIVIDAD = 6;
	CONST UG = 7;
	CONST FF = 8;
	//CONST PROGRAMA = 9;
	CONST USUARIO = 10;
	CONST ARCHIVAR = 11;

//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="entidad", type="integer")
     */
    private $entidad;

	/**
     * @var integer
     *
     * @ORM\Column(name="tipoEntidad", type="integer")
     */
    private $tipoEntidad;	
	
	private static $entityMnager;
	
	public static function setEntityManager($em){
		self::$entityMnager=$em;
	}
	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	// se guarda el id de la entidad
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;    
        return $this;
    }

    // obtiene la entidad, guardado solo esta su id.
    public function getEntidad()
    {		
		$entidad = self::$entityMnager->getRepository($this->getNombredeEntidad($this->getTipoEntidad()))->find($this->entidad);
		
        return $entidad;
    }
	
	public function getNombredeEntidad($t)
    {	
		$tipos = array(self::COMPROBANTE =>"MagypRendicionDeCajaBundle:Comprobante", 
					   self::IMPUTACION =>"MagypRendicionDeCajaBundle:Imputacion", 
					   self::PROVEEDOR =>"MagypRendicionDeCajaBundle:Proveedor", 
					   self::RENDICION =>"MagypRendicionDeCajaBundle:Rendicion",
					   self::PROGRAMA =>"MagypRendicionDeCajaBundle:Programa",
					   self::ACTIVIDAD =>"MagypRendicionDeCajaBundle:Actividad",
					   self::UG =>"MagypRendicionDeCajaBundle:UbicacionGeografica",
					   self::FF =>"MagypRendicionDeCajaBundle:FuenteFinanciamiento",
					   self::USUARIO=>"MagypRendicionDeCajaBundle:Usuario",
					   self::ARCHIVAR=>"MagypRendicionDeCajaBundle:Archivar",
					   
			
			);
        return $tipos[$t];
    }
	
	function __construct($evento, $campo,$tipoEntidad,$entidad){
		$this->setCampo($campo);		
		$this->setEvento($evento);
		$this->setEntidad($entidad->getId()); // setea un id
		$this->setTipoEntidad($tipoEntidad);
		
	}

	public function getTipoEntidad() {
		return $this->tipoEntidad;
	}

	public function setTipoEntidad($tipoEntidad) {
		$this->tipoEntidad = $tipoEntidad;
	}

	public function getSoyEntidad() {
		return true;
	}
}
