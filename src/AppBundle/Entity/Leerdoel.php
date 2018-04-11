<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="leerdoel")
 */
class Leerdoel
{

	 /**
     * @ORM\Column(type="integer",  name="ldid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
		public function setId($id) {$this->id = $id;}
		public function getId() {return $this->id;}

	 /**
     * @ORM\Column(type="string", name="beschrijving")
     */
    protected $beschrijving;
	public function setBeschrijving($var) {$this->beschrijving = $var;}
	public function getBeschrijving() {return $this->beschrijving;}

		/**
		* @ORM\Column(type="integer", name="toetspercentage")
		*/
		protected $toetspercentage;
		public function setToetspercentage($var) {$this->toetspercentage = $var;}
		public function getToetspercentage() {return $this->toetspercentage;}

		/**
		* @ORM\ManyToOne(targetEntity="Context", inversedBy="leerdoelen")
		* @ORM\JoinColumn(name="context", referencedColumnName="ctid")
		*/
		protected $context;
		public function setContext($var) {$this->context = $var;}
		public function getContext() {return $this->context;}

		public function getContextNiveau() {
			if($this->getContext() != null)
				return $this->getContext()->getNiveau();
			else {
				return null;
			}
		}

		/**
		* @ORM\ManyToOne(targetEntity="Bron", inversedBy="leerdoelen")
		* @ORM\JoinColumn(name="bron", referencedColumnName="bid")
		*/
		protected $bron;
		public function setBron($bron) {$this->bron = $bron;}
		public function getBron() {return $this->bron;}

		/**
		* @ORM\ManyToOne(targetEntity="Eindterm", inversedBy="leerdoelen")
		* @ORM\JoinColumn(name="eindterm", referencedColumnName="etid")
		*/
		protected $eindterm;
		public function setEindterm($var) {$this->eindterm = $var;}
		public function getEindterm() {return $this->eindterm;}

	/**
     * @ORM\ManyToOne(targetEntity="Toets", inversedBy="leerdoelen")
     * @ORM\JoinColumn(name="toets", referencedColumnName="tid")
     */
    protected $toets;
	public function setToets($var) {$this->toets = $var;}
	public function getToets() {return $this->toets;}

	/**
     * @ORM\Column(type="integer", name="bloomniveau")
     */
    protected $bloomniveau;
	public function setBloomniveau($var) {$this->bloomniveau = $var;}
	public function getBloomniveau() {return $this->bloomniveau;}

	/**
     * @ORM\Column(type="string", name="code")
     */
    protected $code;
	public function setCode($var) {$this->code = $var;}
	public function getCode() {return $this->code;}

	/**
     * @ORM\OneToMany(targetEntity="Leeruitkomst", mappedBy="leerdoel")
		 * @ORM\OrderBy({"beschrijving" = "ASC"});
     */
    protected $leeruitkomsten;
	public function setLeeruitkomsten($var) {$this->leeruitkomsten = $var;}
	public function getLeeruitkomsten() {return $this->leeruitkomsten;}

	public function __construct()
    {
        $this->leeruitkomsten = new ArrayCollection();
    }

	public function __toString()
	{
		return $this->beschrijving . $this->bron;
	}

	public function getShortmarkup() {
		$markup = "";
		if($this->getToets() != null) {
			$markup .= "(" . $this->getToets()->getCode() . ") ";
		}
		$markup .= substr($this->getBeschrijving(), 0, 50) . "...";
		return $markup;
	}

	public function getAantalLeeruitkomsten() {
		return sizeof($this->leeruitkomsten);
	}

}

?>
