<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="archieftoets")
 */
class ArchiefToets extends Toets
{

  /**
	 * @ORM\ManyToOne(targetEntity="Toets", inversedBy="archieftoetsen")
	 * @ORM\JoinColumn(name="toets", referencedColumnName="tid")
	 */
  protected $toets;
  public function getToets() {
    return $this->toets;
  }
  public function setToets($t) {
    $this->toets = $t;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="course")
  */
  protected $courseText;
  public function getCourseText() {
    return $this->courseText;
  }
  public function setCourseText($c) {
    $this->courseText = $c;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="soort")
  */
  protected $soortText;
  public function getSoortText() {
    return $this->soortText;
  }
  public function setSoortText($c) {
    $this->soortText = $c;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="domein")
  */
  protected $domeinText;
  public function getDomeinText() {
    return $this->domeinText;
  }
  public function setDomeinText($c) {
    $this->domeinText = $c;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="redenmutatie")
  */
  protected $reden;
  public function getReden() {
    return $this->reden;
  }
  public function setReden($c) {
    $this->reden = $c;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="verantwoordelijke")
  */
  protected $verantwoordelijkeText;
  public function getVerantwoordelijkeText() {
    return $this->verantwoordelijkeText;
  }
  public function setVerantwoordelijkeText($c) {
    $this->verantwoordelijkeText = $c;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="reviewer")
  */
  protected $reviewerText;
  public function getReviewerText() {
    return $this->reviewerText;
  }
  public function setReviewerText($c) {
    $this->reviewerText = $c;
    return $this;
  }

  /**
  * @ORM\Column(type="string", name="datumarchief")
  */
  protected $datumArchief;
  public function getDatumArchief() {
    return $this->datumArchief;
  }
  public function setDatumArchief($c) {
    $this->datumArchief = $c;
    return $this;
  }

  public function __construct($toets) {
    $this->setCode($toets->getCode());
    $this->setVoorkennis($toets->getVoorkennis());
    $this->setDuurinminuten($toets->getDuurinminuten());
    $this->setHulpmiddelen($toets->getHulpmiddelen());
    $this->setNaam($toets->getNaam());
    $this->setCode($toets->getCode());
    $this->setEc($toets->getEc());
    $this->setCourseText($toets->getCourse() != null ? $toets->getCourse()->getNaam() : null);
    $this->setVerantwoordelijkeText($toets->getVerantwoordelijke() != null ? $toets->getVerantwoordelijke()->getVolledigeNaam() : null);
    $this->setReviewerText($toets->getReviewer() != null ? $toets->getReviewer()->getVolledigeNaam() : null);
    $this->setSoortText($toets->getSoort() != null ? $toets->getSoort()->getNaam() : null);
    $this->setDomeinText($toets->getDomein() != null ? $toets->getDomein()->getNaam() : null);
    $this->setVeranderaspect($toets->getVeranderaspect());
    $this->setDuurzaamheidsaspect($toets->getDuurzaamheidsaspect());
    $this->setOnderzoeksaspect($toets->getOnderzoeksaspect());
    $this->setInternationaliseringsaspect($toets->getInternationaliseringsaspect());
    $this->setResultaatschaal($toets->getResultaatschaal());
    $this->setMinimaleEis($toets->getMinimaleEis());
    $this->setTaal($toets->getTaal());
    $this->setCompensabel($toets->getCompensabel());
    $this->setWeging($toets->getWeging());
    $this->setDatumArchief(date('Y-m-d H:i:s'));
  }

}

?>
