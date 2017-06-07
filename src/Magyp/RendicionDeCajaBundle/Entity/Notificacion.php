<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Notificacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\NotificacionRepository")
 */
class Notificacion {

    const TODOS = -1;
    const AF = -2;
    const VERSION_ANTERIOR = 1;
    const VERSION_NUEVA = 2;

    // enteros positivos corresponde al id de cada Area.

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Campo Obligatorio")
     * @ORM\Column(name="contenido", type="string", length=255)
     */
    private $contenido;

    /**
     * @var string
     * @Assert\NotBlank(message="Campo Obligatorio")
     * @ORM\Column(name="asunto", type="string", length=255)
     */
    private $asunto;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $usuario
     *
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id",referencedColumnName="id")
     */
    private $usuario;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $area
     * 
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
     */
    private $destino;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $areaDeOrigen
     *
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
     */
    private $areaDeOrigen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/http:\/\//",
     *     message="El link debe compenzar con http://"
     * )  
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Motivo $motivo
     *
     * @ORM\OneToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Motivo", inversedBy="notificacion")
     * @ORM\JoinColumn(name="motivo_id", referencedColumnName="id")
     */
    private $motivo;
    private $motivoTexto;

    /**
     * @var boolean $leido
     *
     * @ORM\Column(name="leido", type="boolean")
     */
    protected $leido;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer")
     */
    private $version;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     *
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion", inversedBy="notificacion")
     * @ORM\JoinColumn(name="rendicion_id", referencedColumnName="id")
     */
    private $rendicion;

    /**
     * @var integer
     *
     * @ORM\Column(name="rendicion_estado", type="integer")
     */
    private $estadorendicion;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return Notificacion
     */
    public function setContenido($contenido) {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido() {
        return $this->contenido;
    }

    /**
     * Set usuario
     *
     * @param \stdClass $usuario
     * @return Notificacion
     */
    public function setUsuario($usuario) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \stdClass 
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set destino
     *
     * @param string $destino
     * @return Notificacion
     */
    public function setDestino($destino) {
        $this->destino = $destino;

        return $this;
    }

    /**
     * Get destino
     *
     * @return string 
     */
    public function getDestino() {
        return $this->destino;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Notificacion
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

    public function paraTodos() {
        $this->destino = self::TODOS;
        return $this;
    }

    public function paraAF() {
        $this->destino = self::AF;
        return $this;
    }

    public function paraArea($area) {
        $this->destino = $area->getId();
        return $this;
    }

    function __construct($usuario = null, $rendicion) {
        if (!is_null($usuario)) { //solo para cuando se guarda
            $estado = $rendicion->getEstado();
            $this->setFecha(new \DateTime());
            $this->setUsuario($usuario);
            $this->setAreaDeOrigen($usuario->getArea());
            $this->setMotivo(null);
            $this->setLeido(0);
            $this->setRendicion($rendicion);
            $this->setVersion(2);
            $this->setEstadoRendicion($estado);
        }
    }

    public function getAsunto() {
        return $this->asunto;
    }

    public function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    public function getAreaDeOrigen() {
        return $this->areaDeOrigen;
    }

    public function setAreaDeOrigen(\Magyp\RendicionDeCajaBundle\Entity\Area $areaDeOrigen) {
        $this->areaDeOrigen = $areaDeOrigen;
    }

    public function getLink() {
        return $this->link;
    }

    public function setLink($link) {

        $this->link = $link;
    }

    public function tieneLink() {
        return strlen($this->link) > 0 ? true : false;
    }

    public function getMotivoTexto() {
        return $this->motivoTexto;
    }

    public function setMotivoTexto($motivoTexto) {
        $this->motivoTexto = $motivoTexto;
        $this->setMotivo(new Motivo($motivoTexto));
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function getRendicion() {
        return $this->rendicion;
    }

    public function getEstadoRendicion() {
        return $this->estadorendicion;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    public function setRendicion($rendicion) {
        $this->rendicion = $rendicion;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function setEstadoRendicion($estado) {
        $this->estadorendicion = $estado;
    }

    public function __toString() {
        return $this->getContenido();
    }

    /**
     * Get asignado
     *
     * @return boolean 
     */
    public function getLeido() {
        return $this->leido;
    }

    /**
     * Set asignado
     *
     * @param boolean $leido
     * @return Notificacion
     */
    public function setLeido($leido) {
        $this->leido = $leido;

        return $this;
    }

}
