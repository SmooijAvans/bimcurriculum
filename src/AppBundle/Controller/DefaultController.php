<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $stats = array();
		$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findAll();
		$leeruitkomsten = $this->getDoctrine()->getRepository('AppBundle:Leeruitkomst')->findAll();
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
		$medewerkers = $this->getDoctrine()->getRepository('AppBundle:Medewerker')->findAll();
		$toetsen = $this->getDoctrine()->getRepository('AppBundle:Toets')->findAll();
		$domeinen = $this->getDoctrine()->getRepository('AppBundle:Domein')->findAll();
		$literatuur = $this->getDoctrine()->getRepository('AppBundle:Literatuur')->findAll();
		$toetsstof = $this->getDoctrine()->getRepository('AppBundle:Toetsstof')->findAll();
		
		$ldlen = sizeof($leerdoelen);
		$sldlen = sizeof($leeruitkomsten);
		$courselen = sizeof($courses);
		$gemiddeld_ld_course = $ldlen / $courselen;
		$gemiddeld_sld_course = $sldlen / $ldlen;
		
		$stats['aantal_leerdoelen'] = $ldlen;
		$stats['aantal_courses'] = $courselen;
		$stats['gem_aantal_ld_courses'] = $gemiddeld_ld_course;
		$stats['gem_aantal_sld_courses'] = $gemiddeld_sld_course;
		$stats['aantal_mw'] = sizeof($medewerkers);
		$stats['aantal_toetsen'] = sizeof($toetsen);
		$stats['gem_aantal_toetsen'] = sizeof($toetsen) / $courselen;
		$stats['aantal_domeinen'] = sizeof($domeinen);
		$stats['aantal_ld_per_domein'] = $ldlen / $stats['aantal_domeinen'];
		$stats['aantal_toetsen_per_domein'] = $stats['aantal_toetsen'] / $stats['aantal_domeinen'];
		$stats['gem_bloom_niveau'] = $this->gemiddeldBloomniveau($leerdoelen);
		$stats['gem_context_niveau'] = $this->gemiddeldContextniveau($leerdoelen);
		$stats['gem_ec'] = $this->gemiddeldEc($toetsen);
		$stats['gem_aantal_t_per_m'] = $stats['aantal_toetsen'] / $stats['aantal_mw'];
		$stats['totaal_literatuur'] = sizeof($literatuur);
		$stats['literatuur_hergebruikt'] = $this->aantalHergebruikteLiteratuurstukken($toetsstof);
		
        return new Response($this->renderview('stats.html.twig', array('stats' => $stats)));
    }
	
	private function gemiddeldBloomniveau($leerdoelen) {
		$cnt = 0;
		$sum = 0;
		foreach($leerdoelen as $leerdoel) {
			if($leerdoel->getBloomniveau() != null) {
				$sum += $leerdoel->getBloomniveau();
			}
			$cnt++;
		}
		return $sum / $cnt;
	}
	
	private function gemiddeldContextniveau($leerdoelen) {
		$cnt = 0;
		$sum = 0;
		foreach($leerdoelen as $leerdoel) {
			if($leerdoel->getContext() != null) {
				$sum += $leerdoel->getContext()->getNiveau();
			}
			$cnt++;
		}
		return $sum / $cnt;
	}
	
	private function gemiddeldEC($toetsen) {
		$cnt = 0;
		$sum = 0;
		foreach($toetsen as $toets) {
			if($toets->getEc() != null) {
				$sum += $toets->getEc();
			}
			$cnt++;
		}
		return $sum / $cnt;
	}
	
	private function aantalHergebruikteLiteratuurstukken($toetsstof) {
		$totals = array();
		foreach($toetsstof as $stof) {
			$allestof = $this->getDoctrine()->getRepository('AppBundle:Toetsstof')->findByLiteratuur($stof->getLiteratuur()->getId());
			if(!array_key_exists($stof->getLiteratuur()->getId(), $totals)) {
				$totals[$stof->getLiteratuur()->getId()] = sizeof($allestof);
			}
		}
		$sum = 0;
		$cnt = 0;
		foreach($totals as $total) {
			$sum += $total;
			$cnt++;
		}
		return $sum / $cnt;
	}

}
