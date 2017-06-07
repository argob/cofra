<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\SessionRepository")
 */
class Session
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable
     *
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id",referencedColumnName="id")
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="sessionid", type="string", length=255)
     */
    private $sessionid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechalogueo", type="datetime")
     */
    private $fechalogueo;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hashuseragent", type="string")
     */
    private $hashuseragent;
	
    /** 
	 * @var string
     *
     * @ORM\Column(name="ip", type="string")
     */
    private $ip;
    
	private $request;
	
	private $ubicaciondiferente;
	
	private $useragent;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set sessionid
     *
     * @param string $sessionid
     * @return Session
     */
    public function setSessionid($sessionid)
    {
        $this->sessionid = $sessionid;
    
        return $this;
    }

    /**
     * Get sessionid
     *
     * @return string 
     */
    public function getSessionid()
    {
        return $this->sessionid;
    }

    /**
     * Set fechalogueo
     *
     * @param \DateTime $fechalogueo
     * @return Session
     */
    public function setFechalogueo($fechalogueo)
    {
        $this->fechalogueo = $fechalogueo;
    
        return $this;
    }

    /**
     * Get fechalogueo
     *
     * @return \DateTime 
     */
    public function getFechalogueo()
    {
        return $this->fechalogueo;
    }
	
	function __construct($usuario,$request) {
		//$request->getSession()-
		$this->setRequest($request);
		$this->setSessionid(session_id());
		$this->setFechalogueo(new \DateTime());
		$this->setUsuario($usuario);
		//$this->setUserAgent($_SERVER['HTTP_USER_AGENT']);
		$this->setUserAgent($request->headers->get('User-Agent'));
		$this->setHashuseragent(sha1($this->getUserAgent()));		
		$this->setUbicaciondiferente(false);
		$this->setIp($request->getClientIp());
		
		
		
	}
	function BorrarSessionesAnteriores($usuario,$em) {
		
		
		$resp = $em->getRepository("MagypRendicionDeCajaBundle:Session")->ultimaSession($usuario,session_id());
		//var_dump($resp);
		$ultimasession = !empty($resp[0]) ? $resp[0] : null ;
		if(!empty($ultimasession)){
		//	var_dump($ultimasession);
			$sessionid = $ultimasession->getSessionid();
			$backup = session_id();
			//var_dump($sessionid);
			session_id($sessionid);
			session_destroy();						
			if($this->esMismaUbicacion($ultimasession) == false){
				$this->getRequest()->getSession()->set("UbicacionDiferente", "true");
				$this->setUbicaciondiferente(true);
			}
			$intervalo = date_diff($ultimasession->getFechalogueo(), new \Datetime());
			$intervaloenminutos= $this->IntervaloaMinutos($intervalo);			
			if($intervaloenminutos < 60){
				//echo "Paso menos de una hora";

				$this->getRequest()->getSession()->set("DobleLogueoEncontrado", "true");			
				
			}
			
		}
		
		//$session = $this->getRequest()->getSession();		
	}

	public function getHashuseragent() {
		return $this->hashuseragent;
	}

	public function setHashuseragent($hashuseragent) {
		$this->hashuseragent = $hashuseragent;
	}

	public function getRequest() {
		return $this->request;
	}

	public function setRequest($request) {
		$this->request = $request;
	}
	public function ActivarAvisodeSession(){
		return $this->getUbicaciondiferente();
	}

	public function getUbicaciondiferente() {
		return $this->ubicaciondiferente;
	}

	public function setUbicaciondiferente($ubicaciondiferente) {
		$this->ubicaciondiferente = $ubicaciondiferente;
	}

	public function getIp() {
		return $this->ip;
	}

	public function setIp($ip) {
		$this->ip = $ip;
	}

	public function esMismaUbicacion($session) {
		$hashactual = sha1($this->getHashuseragent().$this->getIp());
		$hash = sha1($session->getHashuseragent() .$session->getIp());	
	/*	
		var_dump($this->getId());
		var_dump($this->getHashuseragent());
		var_dump($this->getIp());
		var_dump($hashactual);
		echo '---';
		var_dump($session->getId());
		var_dump($session->getHashuseragent());
		var_dump($session->getIp());
		var_dump($hash);
		//die;
	*/	
		return $hashactual === $hash;
	}

	public function getUserAgent() {
		return $this->useragent;
	}

	public function setUserAgent($useragent) {
		$this->useragent = $useragent;
	}
	public function IntervaloaMinutos($intervalo) {
		$y = $intervalo->y;
		$m = ($y*365 + $intervalo->m);
		$d = ($m*30 + $intervalo->d);
		$h = ($d*24 + $intervalo->h);
		$i = ($h*60 + $intervalo->i);
		
		return $i;
	}


}
