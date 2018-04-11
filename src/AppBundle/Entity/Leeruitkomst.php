<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="leeruitkomst")
 */
class Leeruitkomst
{

	 /**
     * @ORM\Column(type="integer", name="luid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	 /**
     * @ORM\Column(type="string", name="beschrijving")
     */
    protected $beschrijving;

	/**
     * @ORM\ManyToOne(targetEntity="Leerdoel", inversedBy="leeruitkomsten")
     * @ORM\JoinColumn(name="leerdoel", referencedColumnName="ldid")
     */
    protected $leerdoel;

	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}
	public function setBeschrijving($var) {$this->beschrijving = $var;}
	public function getBeschrijving() {return $this->beschrijving;}
	public function setLeerdoel($var) {$this->leerdoel = $var;}
	public function getLeerdoel() {return $this->leerdoel;}

	public function __toString() {
		return $this->beschrijving;
	}

}

?>
