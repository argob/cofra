<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Security\Core\User\UserInterface;
//use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Usuario
 *
 * @ORM\Table(name="UsuarioReal")
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\UsuarioRepository")
 */
class Usuario extends \Framework\SeguridadBundle\Entity\Usuario implements \Serializable {

	CONST MAXIMOLOGUEOPERMITIDO = 3;
	CONST AVISOALMAILAPARTIRDE = 2;
	
	/**
	 * @var integer $id
	 *
	 * @ORM\Id
	 */
	protected $id;
	
	/**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $area
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
     */
    private $area;

    /**
     * @var integer $dni
     * @Assert\NotBlank()
     * @ORM\Column(name="dni", type="integer", nullable=true)
     * 
     */
    private $dni;

	/**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", nullable=true)
	 * 
     */
    private $nombre;	

	/**
     * @var string $apellido
     *
     * @ORM\Column(name="apellido", type="string", nullable=true)
	 * 
     */
    private $apellido;	
    
    /**
    * @var boolean $cambiarpassword
    *
    * @ORM\Column(name="cambiarpassword", type="boolean", options={"default":false})
    */
   protected $cambiarpassword;

    /**
     * @var integer $errorlogueo
     * @ORM\Column(name="errorlogueo", type="integer")
     * 
     */
    private $errorlogueo;
    /**
      * @var \Magyp\RendicionDeCajaBundle\Entity\Area $area
      */
    private $areaseleccionada;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	/** Get area
	 *	
	 * @return type Area
	 */
	public function getArea() {
            if (!empty ($this->areaseleccionada)){
                return $this->areaseleccionada;
            }
            return $this->area;
	}
	
	/**	set Area
	 *
	 * @param type $area 
	 */
	public function setArea($area) {
		$this->area = $area;
	}


	
	public function getDni() {
		return $this->dni;
	}

	public function setDni($dni) {
		$this->dni = $dni;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	public function getApellido() {
		return $this->apellido;
	}

	public function setApellido($apellido) {
		$this->apellido = $apellido;
	}

	
	public function __construct(){
        parent::__construct();
        $this->password= md5(rand(77, 800) . date('d-m-Y H:m:s') );
        $this->cambiarpassword= 0;
		$this->setErrorlogueo(0);
	}


    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            ));
    }
    
    public function unserialize($data)
    {
        list(
        $this->id,
        $this->username
        ) = unserialize($data);
    }

     /**
     * Get cambiarpassword
     *
     * @return boolean 
     */
    public function getCambiarPassword()
    {
        return $this->cambiarpassword;
    }

    /**
     * Set cambiarpassword
     *
     * @param boolean $cambiarpassword
     * @return Usuario
     */
    public function setCambiarPassword($cambiarpassword)
    {
        $this->cambiarpassword = $cambiarpassword;
    
        return $this;
    }

    public function perteneceAF()
    {
        return $this->getArea()->esAF();
    }

	public function esRoleAF()
    {
		$roles = $this->getRoles();
		$af= false;
		foreach($roles as $rol){
			if($rol instanceof \Framework\SeguridadBundle\Entity\Accion){
				//echo $rol->getNombre()."<br>";
				if ($rol->getNombre() == "ROLE_AF")$af=true;
			}
			
		}        
		return $af;
    }
    
	public function getErrorlogueo() {
		return $this->errorlogueo;
	}

	public function setErrorlogueo($errorlogueo) {
		$this->errorlogueo = $errorlogueo;
	}

	
	public function IncrementaErrorLogueo() {		
		$this->setErrorlogueo($this->getErrorlogueo() + 1);
	} 		
	
	public function SuperaErroresPermitidos() {		
		return $this->getErrorlogueo() > self::MAXIMOLOGUEOPERMITIDO;
	}
	public function AvisarAlMailExcesosLogueo() {		
		return $this->getErrorlogueo() == self::AVISOALMAILAPARTIRDE;
	}
	
	/**
	 * Esto es para q avise solo una vez
	 * 
	 */
	public function AvisarPorMailBloqueoDeCuenta() {		
		if($this->getErrorlogueo()>= self::MAXIMOLOGUEOPERMITIDO )$this->setbloqueada(1);
		return $this->getErrorlogueo() == self::MAXIMOLOGUEOPERMITIDO;
	}
	
	public function LimpiarErrorLogueo() {
		$this->setErrorlogueo(0);
	} 		
   
    public function __toString() {
        return (string)$this->getApellido().", ".$this->getNombre();
    }
    
    public function isAccountNonLocked() {
        return parent::isAccountNonLocked()/* && ( $this->getArea()->getBorrado() == 0 )*/;
    }
    
    
    /**
     * Get area
     * 
     * @return integer 
     */
    public function getAreaSeleccionada() {

        return $this->areaseleccionada;
    }

    /**
     * Set area
     *
     * @param type $area 
     * @return Area
     */
    public function setAreaSeleccionada($area) {
        $this->areaseleccionada = $area;

        return $this;
    }


}
