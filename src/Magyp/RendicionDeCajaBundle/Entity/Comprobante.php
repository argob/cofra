<?php

namespace Magyp\RendicionDeCajaBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Comprobante
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ComprobanteRepository")
 */
class Comprobante
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion", inversedBy="comprobantes")
     * @ORM\JoinColumn(name="rendicion_id",referencedColumnName="id")
     */
    private $rendicion;

    /**
     * @var string $numero
     *
     * @ORM\Column(name="numero", type="string")
     * @Assert\NotBlank(message="Campo con valor incorrecto o vacio")
     */
    private $numero;


    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Imputacion $imputacion
     * @Assert\NotBlank(message="Debe seleccionar una imputacion")
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Imputacion")
     *
     */
    private $imputacion;

    /**
     * @var string $descripcion
     * @Assert\NotBlank(message="Campo vacio no permitido")
	 * @Assert\Regex("/^\w+/")
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Proveedor $proveedor
     * @Assert\NotBlank(message="Debe seleccionar un Proveedor")
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Proveedor")
     * 
     */
    private $proveedor;
	
    /**
     * @var decimal $importe
     * @Assert\NotBlank(message="Campo vacio no permitido")	 
     * @Assert\Regex(
     *     pattern="/\d+/",
     *     message="Debe ingresar un Numero"
     * )      
     * @ORM\Column(name="importe", type="decimal", precision=7, scale=2, nullable=true)	 
     */
    private $importe;

    /**
     * @var \DateTime $fecha
     * @Assert\NotBlank()
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\TipoFactura $tipofactura
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\TipoFactura")
     */
    private $tipofactura;


    /**
     * @var integer $borrado
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;	
	
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\EventoComprobante[] $eventocomprobantes
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\EventoComprobante", mappedBy="comprobante")
     */
    private $eventocomprobantes;	
	
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
     * Set rendicion
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @return Comprobante
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


     /**
     * Set numero
     *
     * @param integer $numero
     * @return Comprobante
     */
    public function setNumero2($numero)
    {
        $hayImputacion= $this->getImputacion();
        if ( empty($hayImputacion)){
            $this->numero= $numero;
        }else{

            if ( ( $this->getTipofactura()->getId() == 3 ) && ($this->getImputacion()->getDescripcion() == "Movilidad Menor" ) ){
                $this->numero = 0;
            }else{
                $partesNumero = explode("-", $this->numero);
                if ( (is_numeric ($numero)) && ( $numero != "" ) && ( (int)$numero !== 0 ) ){
                    $numero= str_pad( $numero, 8, "0", STR_PAD_LEFT );
                    if (isset ($partesNumero[1])){
                        $this->numero = $partesNumero[0]."-".$numero;
                    }else{
                        if ( isset ($this->numero) ){
                            $this->numero = $this->numero."-".$numero;
                        }
                    }
                }else{
                    $this->numero= null;
                }
            }
        }
        return $this;
    }
    
    
    /**
     * Get numero2
     *
     * @return integer 
     */
    public function getNumero2()
    {
        $partesNumero = explode("-", $this->numero);
        if (! isset( $partesNumero[1] ) ){
            $partesNumero[1]= "";
        }
        return $partesNumero[1];
    }
    
    
     /**
     * Set numero
     *
     * @param integer $numero
     * @return Comprobante
     */
    public function setNumero($numero)
    {
        $hayImputacion= $this->getImputacion();
        if ( ( $this->getTipofactura()->getId() == 3 ) && (!empty ($hayImputacion)) && ($this->getImputacion()->getDescripcion() == "Movilidad Menor" ) ){
            $this->numero = 0;
        }else{
            if ( (is_numeric ($numero)) && ( $numero != "" ) && ( (int)$numero != 0 ) && ( strlen($numero) <= 4 ) ){
                $numero= str_pad($numero, 4, "0", STR_PAD_LEFT);
                $partesNumero = explode("-", $this->numero);
                if ( isset ($partesNumero[1]) ){
                    $this->numero= $numero."-".$partesNumero[1];
                }else{
                    $this->numero = $numero;
                }
            }else{
                $this->numero= null;
            }
        }
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
    
    
    /**
     * Set imputacion
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Imputacion $imputacion
     * @return Comprobante
     */
    public function setImputacion($imputacion)
    {
        $this->imputacion = $imputacion;
    
        return $this;
    }

    /**
     * Get imputacion
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Imputacion
     */
    public function getImputacion()
    {
        return $this->imputacion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Comprobante
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set importe
     *
     * @param decimal $importe
     * @return Comprobante
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    
        return $this;
    }

    /**
     * Get importe
     *
     * @return decimal 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set proveedor
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Proveedor $proveedor
     * @return Comprobante
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Proveedor
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }	
	
	public function getFecha() {
		return $this->fecha;
	}

	public function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	public function getTipofactura() {
		return $this->tipofactura;
	}

	public function setTipofactura($tipofactura) {
		$this->tipofactura = $tipofactura;
	}

	

	public function getBorrado() {
		return $this->borrado;
	}

	public function setBorrado($borrado) {
		$this->borrado = $borrado;
	}

	function __construct() {
		$this->setBorrado(0);
                
                
	}

	public function getEventocomprobantes() {
		return $this->eventocomprobantes;
	}

	public function setEventocomprobantes($eventocomprobantes) {
		$this->eventocomprobantes = $eventocomprobantes;
	}

	public function esRestaurable($fecha){
		$fechaobj = new \DateTime();
		$a = strtotime($fecha);
		$fecha = date('d/M/Y H:i:s', $a);
		$fechaobj = $fechaobj->createFromFormat('d/M/Y H:i:s', $fecha);
		$intervalo =  date_diff($fechaobj, new \DateTime(), true);		
		$tiempo= $intervalo->m*(30*24*60) +$intervalo->d*(24*60) + $intervalo->h*60 + $intervalo->i;		// en minutos
		//var_dump($tiempo);
		if ($tiempo< Evento::TIEMPORESTAURACION) return true;  // si pasaron menos de 10 minutos
		else return false;
	}
	
	public function getfechaultimoevento(){
		return $this->getUltimoevento()->getFecha(); //le pregunta al evento si es restaruable.
	}
	public function getUltimoevento(){
		$eventos = $this->getEventocomprobantes();
		$eventos2 = $eventos->toArray(); 
		$ultimoevento = end($eventos2);
		return $ultimoevento;
	}
	public function getEventos(){
	    return $this->getEventocomprobantes();
	}

	function estaBorrado() {
	    return $this->getBorrado() == 1 ? true : false;
	}
	

}
