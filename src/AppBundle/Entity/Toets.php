<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="toets")
 */
class Toets
{

	 /**
     * @ORM\Column(type="integer", name="tid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	 /**
     * @ORM\Column(type="string", name="code")
     */
    protected $code;

	/**
	 * @ORM\Column(type="string", name="voorkennis", length=255)
	 */
	protected $voorkennis;
	public function getVoorkennis() { return $this->voorkennis; }
	public function setVoorkennis($voorkennis) { $this->voorkennis = $voorkennis; }

	/**
	 * @ORM\Column(type="integer", name="duurinminuten")
	 */
	protected $duurinminuten;
	public function getDuurinminuten() { return $this->duurinminuten; }
	public function setDuurinminuten($duurinminuten) { $this->duurinminuten = $duurinminuten; }

	/**
	 * @ORM\Column(type="string", name="hulpmiddelen", length=255)
	 */
	protected $hulpmiddelen;
	public function getHulpmiddelen() { return $this->hulpmiddelen; }
	public function setHulpmiddelen($hulpmiddelen) { $this->hulpmiddelen = $hulpmiddelen; }


	/**
	 * @ORM\Column(type="string", name="naam", length=100)
	 */
	protected $naam;

	public function getNaam()
	{
		return $this->naam;
	}

	public function setNaam($naam)
	{
		$this->naam = $naam;
	}

	/**
	 * @ORM\OneToMany(targetEntity="Leerdoel", mappedBy="toets")
	 * @ORM\OrderBy({"beschrijving" = "ASC"})
	 */
	protected $leerdoelen;

	public function getLeerdoelen() {
		return $this->leerdoelen;
	}

	public function setLeerdoelen($l) {
		$this->leerdoelen = $l;
	}

	public function getTotalepercentage() {
		$pct = 0;
		foreach($this->leerdoelen as $leerdoel) {
			if($leerdoel->getToetspercentage() > 0) {
				$pct += $leerdoel->getToetspercentage();
			}
		}
		return $pct;
	}

	//hulpfunctie om dubbele leerdoelen te voorkomen bij getLeerdoelen()
	private function hasLeerdoel($arr, $leerdoel) {
		foreach ($arr as $ld) {
			if($ld->getBeschrijving() == $leerdoel->getBeschrijving()) {
				return true;
				break;
			}
		}
	}

	/**
	 * @ORM\ManyToOne(targetEntity="ToetsSoort", inversedBy="toetsen")
	 * @ORM\JoinColumn(name="soort", referencedColumnName="sid")
	 */
	protected $soort;

	public function getSoort()
	{
		return $this->soort;
	}

	public function setSoort($soort)
	{
		$this->soort = $soort;
	}

	/**
	 * @ORM\ManyToOne(targetEntity="Medewerker", inversedBy="gereviewdeToetsen")
	 * @ORM\JoinColumn(name="reviewer", referencedColumnName="mid")
	 */
	protected $reviewer;

	public function getReviewer()
	{
		return $this->reviewer;
	}

	public function setReviewer($reviewer)
	{
		$this->reviewer = $reviewer;
	}

	/**
	 * @ORM\ManyToOne(targetEntity="Domein", inversedBy="toetsen")
	 * @ORM\JoinColumn(name="domein", referencedColumnName="did")
	 */
	protected $domein;

	public function getDomein()
	{
		return $this->domein;
	}

	public function setDomein($domein)
	{
		$this->domein = $domein;
	}

	/**
	 * @ORM\ManyToOne(targetEntity="Medewerker", inversedBy="toetsen")
	 * @ORM\JoinColumn(name="verantwoordelijke", referencedColumnName="mid")
	 */
	protected $verantwoordelijke;

	public function getVerantwoordelijke()
	{
		return $this->verantwoordelijke;
	}

	public function setVerantwoordelijke($verantwoordelijke)
	{
		$this->verantwoordelijke = $verantwoordelijke;
	}

	/**
	 * @ORM\ManyToOne(targetEntity="Course", inversedBy="toetsen")
	 * @ORM\JoinColumn(name="course", referencedColumnName="cid")
	 */
	protected $course;

	public function getCourse()
	{
		return $this->course;
	}

	public function setCourse($course)
	{
		$this->course = $course;
	}

	/**
	 * @ORM\Column(type="float", name="ec")
	 */
	protected $ec;

	/**
	 * @return mixed
	 */
	public function getEc()
	{
		return $this->ec;
	}

	/**
	 * @param mixed $ec
	 */
	public function setEc($ec)
	{
		$this->ec = $ec;
	}

	/**
	 * @ORM\Column(type="boolean", name="veranderaspect")
	 */
	protected $veranderaspect;

	/**
	 * @return mixed
	 */
	public function getVeranderaspect()
	{
		return $this->veranderaspect;
	}

	/**
	 * @param mixed $veranderaspect
	 */
	public function setVeranderaspect($veranderaspect)
	{
		$this->veranderaspect = $veranderaspect;
	}

	/**
	 * @ORM\Column(type="boolean", name="duurzaamheidsaspect")
	 */
	protected $duurzaamheidsaspect;

	/**
	 * @return mixed
	 */
	public function getDuurzaamheidsaspect()
	{
		return $this->duurzaamheidsaspect;
	}

	/**
	 * @ORM\Column(type="boolean", name="onderzoeksaspect")
	 */
	protected $onderzoeksaspect;

	/**
	 * @return mixed
	 */
	public function getOnderzoeksaspect()
	{
		return $this->onderzoeksaspect;
	}

	/**
	 * @param mixed $onderzoeksaspect
	 */
	public function setOnderzoeksaspect($onderzoeksaspect)
	{
		$this->onderzoeksaspect = $onderzoeksaspect;
	}

	/**
	 * @param mixed $duurzaamheidsaspect
	 */
	public function setDuurzaamheidsaspect($duurzaamheidsaspect)
	{
		$this->duurzaamheidsaspect = $duurzaamheidsaspect;
	}

	/**
	 * @ORM\Column(type="boolean", name="internationaliseringsaspect")
	 */
	protected $internationaliseringsaspect;
	public function getInternationaliseringsaspect() { return $this->internationaliseringsaspect; }
	public function setInternationaliseringsaspect($internationaliseringsaspect)
	{ $this->internationaliseringsaspect = $internationaliseringsaspect; }

	/**
	 * @ORM\Column(type="string", name="resultaatschaal")
	*/
	protected $resultaatschaal;
	public function setResultaatschaal($schaal) {$this->resultaatschaal = $schaal;}
	public function getResultaatschaal() {return $this->resultaatschaal;}

	/**
	 * @ORM\Column(type="string", name="weging")
	*/
	protected $weging;
	public function setWeging($weging) {$this->weging = $weging;}
	public function getWeging() {return $this->weging;}

	/**
	 * @ORM\Column(type="string", name="minimale_eis")
	*/
	protected $minimaleEis;
	public function setMinimaleEis($eis) {$this->minimaleEis = $eis;}
	public function getMinimaleEis() {return $this->minimaleEis;}

	/**
	 * @ORM\Column(type="string", name="taal")
	*/
	protected $taal;
	public function setTaal($taal) {$this->taal = $taal;}
	public function getTaal() {return $this->taal;}

	/**
	 * @ORM\Column(type="boolean", name="compensabel")
	*/
	protected $compensabel;
	public function setCompensabel($compensabel) {$this->compensabel = $compensabel;}
	public function getCompensabel() {return $this->compensabel;}

	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}
	public function setCode($var) {$this->code = $var;}
	public function getCode() {return $this->code;}

	public function __toString() {
		$course = "";
		if($this->course != NULL) {
			$course = "periode " . $this->course->getJaar() . "." . $this->course->getPeriode();
		}
		$retval = $this->naam . (strlen($course) > 1 ? (" / " . $course) : "");
		return $retval;
	}

	public function getDisplayValue() {
		return $this->naam . " (" . $this->code . ")";
	}

	/**
	 * @ORM\OneToMany(targetEntity="Toetsstof", mappedBy="toets")
	 */
	protected $toetsstof;
	public function setToetsstof($var) {$this->toetsstof = $var;}
	public function getToetsstof() {return $this->toetsstof;}

	/**
	 * @ORM\OneToMany(targetEntity="ArchiefToets", mappedBy="toets")
	 * @ORM\OrderBy({"datumArchief" = "DESC"})
	 */
	protected $archieftoetsen;
	public function setArchieftoetsen($archieftoetsen) {$this->archieftoetsen = $archieftoetsen;}
	public function getArchieftoetsen() {return $this->archieftoetsen;}

	public function __construct()
    {
        $this->leeruitkomsten = new ArrayCollection();
							$this->toetsstof = new ArrayCollection();
							$this->archieftoetsen = new ArrayCollection();
							//$this->leerdoelen = new ArrayCollection();
    }

}

?>
