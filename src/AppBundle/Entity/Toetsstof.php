<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="toetsstof")
 */
class Toetsstof
{

	/**
	* * @ORM\Id()
	 * @ORM\ManyToOne(targetEntity="Toets", inversedBy="toetsstof")
	 * @ORM\JoinColumn(name="toets", referencedColumnName="tid")
	 */
	protected $toets;

	/**
	* * @ORM\Id()
	 * @ORM\ManyToOne(targetEntity="Literatuur", inversedBy="toetsstof")
	 * @ORM\JoinColumn(name="literatuur", referencedColumnName="ltid")
	 */
	protected $literatuur;

	/**
	 * @ORM\Column(type="string", name="stof")
	 */
	protected $stof;

	public function getToets(){
		return $this->toets;
	}

	public function setToets($toets){
		$this->toets = $toets;
	}

	public function getLiteratuur(){
		return $this->literatuur;
	}

	public function setLiteratuur($literatuur){
		$this->literatuur = $literatuur;
	}

	public function getStof(){
		return $this->stof;
	}

	public function setStof($stof){
		$this->stof = $stof;
	}

	public function __toString() {
		return $this->literatuur->getAuteur() . " " . $this->literatuur->getTitel();
	}

}

?>
