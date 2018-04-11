<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="eindterm")
 */
class Eindterm
{

	/**
	* @ORM\Column(type="integer",  name="etid")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}

	/**
	 * @ORM\Column(type="text", name="beschrijving")
	 */
	protected $beschrijving;

	public function getBeschrijving(){
		return $this->beschrijving;
	}

	public function setBeschrijving($beschrijving){
		$this->beschrijving = $beschrijving;
	}

	public function __toString() {
		return $this->beschrijving;
	}

	/**
	* @ORM\OneToMany(targetEntity="Leerdoel", mappedBy="eindterm", cascade={"persist"})
  */
	protected $leerdoelen;
	public function setLeerdoelen($v) {$this->leerdoelen = $v;}
	public function getLeerdoelen() {return $this->leerdoelen;}

}

?>
