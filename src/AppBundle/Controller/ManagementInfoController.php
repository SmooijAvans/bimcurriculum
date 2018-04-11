<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Bron;
use AppBundle\Entity\BronHerkomst;
use AppBundle\Form\Type\BronType;
use AppBundle\Form\Type\BronHerkomstType;

class ManagementInfoController extends Controller
{

	 /**
     * @Route("/info/toetsen/course", name="info_toetsen")
     */
    public function toetsOverzicht(Request $request)
    {
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findBy(array(), array('jaar'=>'asc', 'periode' => 'asc'));

        return new Response($this->renderview('toetsoverzicht_course_select.html.twig', array(
            'courses' => $courses,
        )));
    }

	/**
	* @Route("/info/verbeteracties", name="info_verbeteracties")
	*/
	public function overzichtVerbeteracties(Request $request)
	{
		$repos = $this->getDoctrine()->getRepository('AppBundle:Verbeteractie');
		$query = $repos->createQueryBuilder('v');
		$query->join('v.toets', 't');
		$query->join('t.course', 'c');
		$query->join('v.medewerker', 'm');
		$query->orderBy('c.jaar', 'ASC');
		$query->addOrderBy('c.periode', 'ASC');
		$query->addOrderBy('t.course', 'ASC');
		$query->addOrderBy('t.naam', 'ASC');
		$query->addOrderBy('m.volledigeNaam', 'ASC');
		$acties = $query->getQuery()->getResult();

		return new Response($this->renderview('verbeteracties.html.twig', array(
			'acties' => $acties,
			)));
	}

	/**
	* @Route("/info/medewerkers/toetsoverzicht", name="info_medewerker_toets_overzicht")
	*/
	public function toetsenPerMedewerker(Request $request)
	{
		$medewerkers = $this->getDoctrine()->getRepository('AppBundle:Medewerker')->findAll();
		return new Response($this->renderview('medewerkersentoetsen.html.twig', array('medewerkers' => $medewerkers)));
	}

	/**
	* @Route("/info/courses/toetsoverzicht", name="info_course_toets_overzicht")
	*/
	public function toetsenPerCourse(Request $request)
	{
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course');
		$query = $courses->createQueryBuilder('c')
		->addOrderBy('c.jaar', 'ASC')
		->addOrderBy('c.periode', 'ASC')
		->getQuery();
		$courses = $query->getResult();
		return new Response($this->renderview('coursesentoetsen.html.twig', array('courses' => $courses)));
	}

    /**
     * @Route("/info/toetsen/course/{id}", name="info_toetsen_in_course")
     */
    public function toetsOverzichtByCourseId(Request $request, $id)
    {
        $toetsen = $this->getDoctrine()->getRepository('AppBundle:Toets')->findByCourse($id);

        return new Response($this->renderview('toetsoverzicht.html.twig', array(
            'toetsen' => $toetsen,
        )));
    }

		/**
		* @Route("/info/eindtermen/", name="info_eindtermen")
		*/
		public function getEindtermen(Request $request)
		{

			$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findAll();

			uasort($leerdoelen, function($ld1, $ld2) {
				if($ld1->getContextNiveau() == null)
					return 1;
				else if ($ld2->getContextNiveau() == null)
					return -1;
				else if(($ld1->getContextNiveau() * $ld1->getBloomniveau()) > ($ld2->getContextNiveau() * $ld2->getBloomniveau()))
					return -1;
				else if(($ld1->getContextNiveau() * $ld1->getBloomniveau()) < ($ld2->getContextNiveau() * $ld2->getBloomniveau()))
						return 1;
				else
					return 0;
			});
			return new Response($this->renderview('eindtermen_leerdoelen.html.twig', array('leerdoelen' => $leerdoelen)));
		}

    /**
    * @Route("/info/studiegids/", name="info_studiegids")
    */
    public function getStudiegids(Request $request)
    {
      $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
      return new Response($this->renderview('studiegids.html.twig', array('courses' => $courses)));
    }



}
?>
