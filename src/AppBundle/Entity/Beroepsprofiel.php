<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="beroepsprofiel")
 */
class Beroepsprofiel
{

	/**
	* @ORM\Column(type="integer",  name="bpid")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}

	/**
	 * @ORM\Column(type="string", name="beschrijving", length=255)
	 */
	protected $beschrijving;

	/**
	 * @ORM\Column(type="text", name="volledigebeschrijving")
	 */
	protected $volledigeBeschrijving;

	public function getBeschrijving(){
		return $this->beschrijving;
	}

	public function setBeschrijving($beschrijving){
		$this->beschrijving = $beschrijving;
	}

	public function getVolledigeBeschrijving(){
		return $this->volledigeBeschrijving;
	}

	public function setVolledigeBeschrijving($beschrijving){
		$this->volledigeBeschrijving = $beschrijving;
	}

	public function __toString() {
		return $this->beschrijving;
	}

}

?>
