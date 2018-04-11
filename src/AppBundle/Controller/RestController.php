<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course;
use AppBundle\Entity\Leerdoel;
use AppBundle\Entity\BronHerkomst;
use AppBundle\Entity\Leeruitkomst;
use AppBundle\Form\Type\BronType;
use AppBundle\Form\Type\BronHerkomstType;
use Symfony\Component\Console\Logger\ConsoleLogger;

class RestController extends Controller
{

	 /**
     * @Route("/rest/courses/all", name="rest_courses_all")
     */
    public function getAllCourses(Request $request)
    {
		    $bronnen = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
    }

	/**
	* @Route("/rest/leerdoel/{ldid}/redirect/course/{cid}", name="rest_courses_all")
	*/
	public function restLeerdoelRedirectCourse(Request $request, $ldid = NULL, $cid = NULL)
    {
		$em = $this->getDoctrine()->getManager();
		$leerdoel = $em->getRepository('AppBundle:Leerdoel')->findOneById($ldid);
		$course = $em->getRepository('AppBundle:Course')->findOneById($cid);
		$leerdoel->setCourse($course);
		$leeruitkomsten = $leerdoel->getLeeruitkomsten();
		foreach ($leeruitkomsten as $lu) {
			$em->remove($lu);
		}
		$em->flush();
		return new Response("1");
    }

	/**
	* @Route("/rest/action/savetoetsenmetleerdoelen", name="rest_action_savetoetsenleerdoelen")
	*/
	public function restActionSaveToetsMetLeerdoelen(Request $request)
    {
		try {
			if(!isset($_POST['leerdoelen']))
				return new Response("Warning: no POST vars provided.");
			$leerdoelen = $_POST['leerdoelen'];
			$em = $this->getDoctrine()->getManager();
			foreach ($leerdoelen as $leerdoel) {
				$ld = $em->getRepository('AppBundle:Leerdoel')->findOneById($leerdoel[1]);
				if(isset($ld) && isset($leerdoel[0])) {
					$toets = $em->getRepository('AppBundle:Toets')->findOneById($leerdoel[0]);
					$ld->setToets($toets);
					$em->persist($ld);
				}
			}
			$em->flush();
			return new Response("1"); //1 = OK, 0 = NOK
		} catch(Exception $e) {
			return new Response("Exception occured: " . $e->getMessage());
		}
    }

	/**
	* @Route("/rest/action/saveeindtermenmetleerdoelen", name="rest_action_saveeindtermenleerdoelen")
	*/
	public function restActionSaveEindtermMetLeerdoelen(Request $request)
    {
		try {
			if(!isset($_POST['leerdoelen']))
				return new Response("Warning: no POST vars provided.");
			$leerdoelen = $_POST['leerdoelen'];
			$em = $this->getDoctrine()->getManager();
			foreach ($leerdoelen as $leerdoel) {
				$ld = $em->getRepository('AppBundle:Leerdoel')->findOneById($leerdoel[1]);
				if(isset($ld) && isset($leerdoel[0])) {
					$eindterm = $em->getRepository('AppBundle:Eindterm')->findOneById($leerdoel[0]);
					$ld->setEindterm($eindterm);
					$em->persist($ld);
				}
			}
			$em->flush();
			return new Response("1"); //1 = OK, 0 = NOK
		} catch(Exception $e) {
			return new Response("Exception occured: " . $e->getMessage());
		}
    }

}
?>
