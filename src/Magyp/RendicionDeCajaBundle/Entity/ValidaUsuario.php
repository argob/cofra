<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\ValidaUsuario
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="usuario_motivo_idx", columns={"usuario_id", "motivo"})})
 * @ORM\Entity
 */
class ValidaUsuario {

	const ALTA = 1;
	const CANCELACION = 2;
	const MODIFICACION_CLAVE = 3;

	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var Usuario $usuario
	 *
	 * @ORM\ManyToOne(targetEntity="Usuario")
	 * @ORM\JoinColumn(name="usuario_id",referencedColumnName="id")
	 */
	private $usuario;

	/**
	 * @var string $hash
	 *
	 * @ORM\Column(name="hash", type="string", length=255)
	 */
	private $hash;

	/**
	 * @var datetime $creado
	 *
	 * @ORM\Column(name="creado", type="datetime")
	 */
	private $creado;

	/**
	 * @var integer $motivo
	 * 
	 * @ORM\Column(name="motivo", type="integer")
	 */
	private $motivo;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set usuario
	 *
	 * @param UsuarioExterno $usuario
	 */
	public function setUsuario(Usuario $usuario) {
		$this->usuario = $usuario;
	}

	/**
	 * Get usuario
	 *
	 * @return UsuarioExterno
	 */
	public function getUsuario() {
		return $this->usuario;
	}

	/**
	 * Set hash
	 *
	 * @param string $hash
	 */
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	 * Get hash
	 *
	 * @return string 
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Set creado
	 *
	 * @param \Datetime $creado
	 */
	public function setCreado($creado) {
		$this->creado = $creado;
	}

	/**
	 * Get creado
	 *
	 * @return \Datetime 
	 */
	public function getCreado() {
		return $this->creado;
	}

	public function getMotivo() {
		return $this->motivo;
	}

	public function setMotivo($motivo) {
		$this->motivo = $motivo;
	}

	/**
	 *
	 * @param Usuario $usuario 
	 */
	function __construct(Usuario $usuario, $motivo) {
		$this->setCreado(new \DateTime());
		$this->usuario = $usuario;
		$this->hash = md5(rand(77, 800) . $this->getCreado()->format("Ymd"));
		$this->motivo = $motivo;
	}

}