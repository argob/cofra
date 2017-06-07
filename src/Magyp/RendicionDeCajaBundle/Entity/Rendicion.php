<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Rendicion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\RendicionRepository")
 */
class Rendicion {

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
     * @Assert\NotBlank()
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;
    /**
     * @var string $expediente
     * @Assert\NotBlank()
     * @ORM\Column(name="expediente", type="string", length=255)
     */
    private $expediente;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable
     *
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="responsable_id",referencedColumnName="id")
     */
    private $responsable;

    /**
     * @var decimal $totalRendicion
     * @Assert\NotBlank()
     * @ORM\Column(name="totalRendicion", type="decimal", precision=9, scale=2, nullable=true)	 
     */
    private $totalRendicion;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Comprobante[] $comprobantes  
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Comprobante", mappedBy="rendicion")
     */
    private $comprobantes;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $Area
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
     * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     * 
     */
    private $area;

    /**
     * @var integer $idultimanotificacion
     * @ORM\Column(name="idultimanotificacion", type="integer", nullable=true)
     */
    private $idultimanotificacion;

    /**
     * @var integer $borrado
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;

    /**
     * @var integer $estado
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Liquidacion[] $liquidaciones  
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Liquidacion", mappedBy="rendicion")
     */
    private $liquidaciones;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Notificacion $notificacion
     *
     * @ORM\OneToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Notificacion", inversedBy="rendicion")
     * 
     */
    protected $notificacion;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Rendicion
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set expediente
     *
     * @param string $expediente
     * @return Rendicion
     */
    public function setExpediente($expediente) {
        //preg_match("/^(?P<nro>\d{1,7})\/(?P<anio>\d{4})$/","00144/2013");
        //


	// validar si termina /AÑO, sino error.
        // validar si antes de /AÑO hay 7 numeros o menos
        // validar si contiene S05:
        //preg_match("[0-9]{1,7}/[0-9]{4}",$expediente);
        //preg_match("/^(\d{1,7})\/(\d{4})$/",$expediente);
        //preg_match("",$expediente);
//	$exp = str_pad($expediente, 7, '0', STR_PAD_LEFT );
//	echo $exp;
//	die();
        //$r = preg_match("/^(?P<nro>\d{1,7})\/(?P<anio>\d{4})$/",$expediente,$resultado);
        /*$r = preg_match("/^(S05:)?(?P<nro>\d{1,7})\/(?P<anio>\d{4})$/", $expediente, $resultado);
        if ($r) {*/
            //var_dump($resultado);
        /*    $expediente = str_pad($resultado['nro'], 7, '0', STR_PAD_LEFT) . '/' . $resultado['anio'];*/
            //echo $expediente ."<br><Br>";
        /*}*/
        //die();
        //$expediente str_pad($expediente, 7, '0', STR_PAD_LEFT );
        //die($expediente);
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return string 
     */
    public function getExpediente() {
        return $this->expediente;
    }

    /**
     * @return string 
     */
    public function getExpedienteCompleto() {
        return $this->expediente;
        //return $this->getPrefijo() . substr($this->getExpediente(), -12, 12);
    }

    public function getPrefijo() {
        return "";
    }

    /**
     * @return string 
     */
    public function getExpedienteCompletoCorto() {
        return $this->expediente;
        //return $this->getPrefijoCorto() . substr($this->getExpediente(), -12, 12);
    }

    public function getPrefijoCorto() {
        return "";
    }

    /**
     * Set responsable
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable
     * @return Rendicion
     */
    public function setResponsable($responsable) {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario 
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * Set totalRendicion
     *
     * @param decimal $totalRendicion
     * @return Rendicion
     */
    public function setTotalRendicion($totalRendicion) {
        $this->totalRendicion = $totalRendicion;

        return $this;
    }

    /**
     * Get totalRendicion
     *
     * @return decimal 
     */
    public function getTotalRendicion() {
        $total = 0;
        $comprobantes = $this->getComprobantes();
        foreach ($comprobantes as $comprobante) {
            if (!$comprobante->estaBorrado())
                $total+= $comprobante->getImporte();
        }
        return $total;
    }

    function __toString() {
        return $this->getExpediente();
    }

    /**
     * Get Comprobantes
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Comprobante[] 
     */
    public function getComprobantes() {

        return $this->comprobantes->toArray();
    }

//
//	/**
//     * Set Comprobantes
//     *
//     * @param \Magyp\RendicionDeCajaBundle\Entity\Comprobante[] $Comprobantes
//     * @return \Magyp\RendicionDeCajaBundle\Entity\Comprobante[]
//     */
//	public function setComprobantes($Comprobantes) {
//		$this->Comprobantes = $Comprobantes;
//	}

    function __construct() {
        $this->comprobantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setTotalRendicion(0);
        $this->setBorrado(0);
        $this->setEstado(1);
    }

    /**
     * Set Area
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @return Proveedor
     */
    public function setArea($area) {
        $this->area = $area;

        return $this;
    }

    /**
     * Get Area
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Area 
     */
    public function getArea() {
        return $this->area;
    }

    public function Actualizar() {
        $this->setTotalRendicion($this->getImporteTotal());
    }

    public function getImporteTotal() {
        $Comprobantes = $this->getComprobantes();
        $total = 0;
        foreach ($Comprobantes as $Comprobante) {
            // echo $Comprobante->getBorrado()." - ".$Comprobante->getImporte()." / <br/>";
            if ($Comprobante->getBorrado() == 0) {
                $total += $Comprobante->getImporte();
            }
        }
        return $total;
    }

    public function getBorrado() {
        return $this->borrado;
    }

    public function setBorrado($borrado) {
        $this->borrado = $borrado;
    }

//	public function getTipo() {
//		return array(0 =>'S05');
//	}
//	public function setTipo() {
//		//nada
//	}

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        //	die;
        $this->estado = $estado;
    }

    public function esModificable() {
        $estado = $this->getEstado();
        if ($estado == 0)
            $estado = Estado::NUEVO; // esto es para los viejos datos q tienen el estadoen 0, luego borrar esta linea.
        if ($estado == Estado::NUEVO || $estado == Estado::ACORREGIR)
            return true;
        else
            return false;
    }

    public function esModificablePorAF() {
        $estado = $this->getEstado();
        if ($estado == 0)
            $estado = Estado::NUEVO; // esto es para los viejos datos q tienen el estadoen 0, luego borrar esta linea.
        if ($estado == Estado::NUEVO || $estado == Estado::ACORREGIR || $estado == Estado::ACEPTADO || $estado == Estado::ENVIADO)
            return true;
        else
            return false;
    }

    public function isAceptada() {
        return $this->getEstado() == Estado::ACEPTADO;
    }

    public function isEnviado() {
        return $this->getEstado() == Estado::ENVIADO;
    }

    public function isAcorregir() {
        return $this->getEstado() == Estado::ACORREGIR;
    }

    public function isNuevo() {
        return $this->getEstado() == Estado::NUEVO;
    }

    public function isAtesoreria() {
        return $this->getEstado() == Estado::ATESORERIA;
    }

    public function isArchivada() {
        return $this->getEstado() == Estado::ARCHIVADA;
    }

    public function isAapagar() {
        return $this->getEstado() == Estado::APAGAR;
    }

    public function isApagado() {
        return $this->getEstado() == Estado::PAGADO;
    }

    public function getEstadoNombre() {
        $estados = array(
            Estado::DESCONOCIDO => "Desconocido", // agregado por q habia estados en 0
            Estado::NUEVO => "Nuevo",
            Estado::ENVIADO => "Enviado",
            Estado::ACEPTADO => "Aceptado",
            Estado::ACORREGIR => "A Corregir",
            Estado::ATESORERIA => "A Tesoreria",
            Estado::ARCHIVADA => "Archivada",
            Estado::APAGAR => "A Pagar",
            Estado::PAGADO => "Pagado",
        );
        return $estados[$this->estado];
    }

    /**
     * Obtiene el ID de la notificacion que informa que es lo q se debe corregir.
     * Solo existe si paso por un estado de correccion.
     * 
     * @return integer
     */
    public function getIdultimanotificacion() {
        return $this->idultimanotificacion;
    }

    public function setIdultimanotificacion($idultimanotificacion) {
        $this->idultimanotificacion = $idultimanotificacion;
    }

    public function getLiquidaciones() {
        return $this->liquidaciones;
    }

    public function setLiquidaciones(\Magyp\RendicionDeCajaBundle\Entity\Liquidacion $liquidaciones) {
        $this->liquidaciones = $liquidaciones;
    }

}
