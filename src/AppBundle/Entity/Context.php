<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="context")
 */
class Context
{

	/**
	* @ORM\Column(type="integer", name="ctid")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}

	/**
	* @ORM\OneToMany(targetEntity="Leerdoel", mappedBy="context", cascade={"persist"})
  */
	protected $leerdoelen;
	public function setLeerdoelen($v) {$this->leerdoelen = $v;}
	public function getLeerdoelen() {return $this->leerdoelen;}

	/**
	* @ORM\Column(type="string", name="beschrijving")
	*/
	protected $beschrijving;
	public function setBeschrijving($var) {$this->beschrijving = $var;}
	public function getBeschrijving() {return $this->beschrijving;}

	/**
	* @ORM\Column(type="integer", name="niveau")
	*/
	protected $niveau;
	public function setNiveau($v) {$this->niveau = $v;}
	public function getNiveau() {return $this->niveau;}

	public function __construct()
	{
		$this->leerdoelen = new ArrayCollection();
	}

	public function __toString() {
		return "Niveau " . $this->getNiveau() . ": " . $this->getBeschrijving();
	}
}

?>
