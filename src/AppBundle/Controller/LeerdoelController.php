<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Leerdoel;
use AppBundle\Entity\Course;
use AppBundle\Entity\Context;
use AppBundle\Form\Type\LeerdoelType;
use AppBundle\Form\Type\ContextType;
use AppBundle\Form\Type\CourseSelectType;
use AppBundle\Form\Type\LeerdoelPercentageType;

class LeerdoelController extends Controller
{

	/**
	* @Route("/view/leerdoel/filter/bron/{bronid}", name="view_leerdoel_filter_bron")
	*/
	public function leerdoelOverzichtBronId(Request $request, $bronid = NULL)
	{
		if(isset($bronid) && $bronid != null) {
			$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findByBron($bronid);
		}
		else {
			$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findAll();
		}
		$bronnen = $this->getDoctrine()->getRepository('AppBundle:Bron')->findAll();
		$title = "";
		if(isset($bronid) && $bronid != null) {
			$bron = $this->getDoctrine()->getRepository('AppBundle:Bron')->find($bronid);
			$title = ($bron != null) ? $bron->getNaam() : "";
		}
		return new Response($this->renderview('leerdoelen.html.twig', array('leerdoelen' => $leerdoelen, 'bronnen' => $bronnen, 'title' => $title)));
	}

	/**
	* @Route("/edit/leerdoel/toetspercentage/{ldid}", name="edit_leerdoel_toetspercentage")
	*/
	public function leerdoelPercentage(Request $request, $ldid = NULL)
	{
		$leerdoel = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->find($ldid);
		$form = $this->createForm(LeerdoelPercentageType::class, $leerdoel);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($leerdoel);
			$em->flush();
			return $this->redirect($this->generateUrl("view_course_leerdoelen", array('courseid' => $leerdoel->getToets()->getCourse()->getId())) . "#" . $leerdoel->getToets()->getId());
		}
		return new Response($this->renderview('form.html.twig', array('title' => 'Toetspercentage bewerken voor leerdoel','form' => $form->createView())));
	}

		/**
		* @Route("/view/leerdoel", name="view_leerdoel")
		*/
    public function leerdoelOverzicht(Request $request)
    {
			$bronnen = $this->getDoctrine()->getRepository('AppBundle:Bron')->findAll();
			$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
			if($request->request->get('bronid') != null) {
				$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findByBron($request->request->get('bronid'));
				$bron = $this->getDoctrine()->getRepository('AppBundle:Bron')->find($request->request->get('bronid'));
				$title = ($bron != null) ? $bron->getNaam() : "";
			}
			elseif($request->request->get('zoekarg') != null) {
				$arg = $request->request->get('zoekarg');
				$em = $this->getDoctrine()->getManager();
				$query = $em->createQuery(
					'SELECT ld
					FROM AppBundle:Leerdoel ld
					WHERE ld.beschrijving LIKE :arg'
				);
				$query->setParameter('arg', "%" . $arg . "%");
				$leerdoelen = $query->getResult();
				$title = "Gezocht op \"" . (strlen($arg) > 15 ? substr($arg, 0, 15) . "...": $arg) . "\"";
			}
			elseif($request->request->get('omissies') != null) {
				$arg = $request->request->get('omissies');
				if($arg == "bloom") {
					$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findByBloomniveau(null);
					$title = "Leerdoelen zonder bloom niveau";
				}
				if($arg == "context") {
					$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findByContext(null);
					$title = "Leerdoelen zonder context";
				}
				if($arg == "toets") {
					$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findByToets(null);
					$title = "Leerdoelen zonder course";
				}
			}
			elseif($request->request->get('course') != null) {
				$arg = $request->request->get('course');
				
				$course = $this->getDoctrine()->getRepository('AppBundle:Course')->findOneById($arg);

				$repos = $this->getDoctrine()->getRepository('AppBundle:Leerdoel');
				$query = $repos->createQueryBuilder('l');
				$query->join('l.toets', 't');
				$query->where('t.course = :pCourse');
				$query->setParameter(':pCourse', $arg);
				$query->orderBy('l.toets', 'ASC');
				$leerdoelen = $query->getQuery()->getResult();

				if($course != null)
					$title = "Leerdoelen voor course " . $course->getNaam();
				else if($arg == "geencourse")
					$title = "Leerdoelen zonder course";
			}
			else {
				$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findAll();
				$title = "Alle leerdoelen";
			}

			//de arraycollectie in memory sorteren, omdat bovenstaande code te divers is in if/else branches
			//TO DO: voor performance issues op query/database niveau sorteren in de toekomst
			uasort($leerdoelen, function ($a, $b) {
				if($a->getToets() == NULL) {
					return 1;
				}
				if($a->getToets() != NULL && $b->getToets() != NULL && 
					$a->getToets()->getCourse() != NULL && $b->getToets()->getCourse() != NULL) {

					$jaar_a = $a->getToets()->getCourse()->getJaar();
					$jaar_b = $b->getToets()->getCourse()->getJaar();
					if($jaar_a == $jaar_b) {
						$periode_a = $a->getToets()->getCourse()->getPeriode();
						$periode_b = $b->getToets()->getCourse()->getPeriode();
						if($periode_a == $periode_b) {
							return ($a->getToets()->getNaam() < $b->getToets()->getNaam()) ? -1 : 1;
						}
						else {
							return ($periode_a < $periode_b) ? -1 : 1;
						}
					}
					else {
						return ($jaar_a < $jaar_b) ? -1 : 1;
					}
				}
				else {
					return -1;
				}
			});

			return new Response($this->renderview('leerdoelen.html.twig', array('leerdoelen' => $leerdoelen, 'courses' => $courses, 'bronnen' => $bronnen, 'title' => $title)));
    }

		/**
      * @Route("/organize/leerdoel/toets", name="organize_leerdoel_toets")
      */
     public function organiseerLeerdoelen(Request $request, $bronid = NULL)
     {
			$toetsen = $this->getDoctrine()->getRepository('AppBundle:Toets');
			$bronnen = $this->getDoctrine()->getRepository('AppBundle:Bron');
			$query2 = $toetsen->createQueryBuilder('t');
			$query = $bronnen->createQueryBuilder('b');

			//zoekformulier
			$courseSearchForm = $this->createForm(CourseSelectType::class);
			$courseSearchForm->handleRequest($request);
			if($courseSearchForm->isSubmitted()) {
				$course = $courseSearchForm['courses']->getData();
				$query2->where('t.course = ' . $course->getId());
				$bronnen = $courseSearchForm['bronnen']->getData();
				if($bronnen != null && trim($bronnen) != "") {
					$query->where('b.naam LIKE \'%' . $bronnen . '%\'');
				}
			}
			$query2->join('t.course', 'c')
				->addOrderBy('c.jaar', 'ASC')
				->addOrderBy('c.periode', 'ASC');
			$toetsen = $query2->getQuery()->getResult();

			$query->join('b.leerdoelen', 'lds')
				->addOrderBy('b.id', 'ASC');
			$bronnen = $query->getQuery()->getResult();
				return new Response($this->renderview('sub_leerdoelen.html.twig', array('bronnen' => $bronnen, 'toetsen' => $toetsen, 'coursezoekform' => $courseSearchForm->createView())));
     }

	/**
	* @Route("/organize/leerdoel/eindterm", name="organize_leerdoel_eindterm")
	*/
	 public function alleEindtermen(Request $request, $bronid = NULL)
	 {
		 $bronnen = $this->getDoctrine()->getRepository('AppBundle:Bron');
		 $query = $bronnen->createQueryBuilder('b')
		 ->leftJoin('b.leerdoelen', 'lds')
		 ->addOrderBy('b.id', 'ASC')
		 ->getQuery();
		 $bronnen = $query->getResult();

		 $eindtermen = $this->getDoctrine()->getRepository('AppBundle:Eindterm');
		 $query2 = $eindtermen->createQueryBuilder('e')
		 ->leftJoin('e.leerdoelen', 'lds')
		 ->addOrderBy('e.beschrijving', 'ASC')
		 ->getQuery();
		 $eindtermen = $query2->getResult();
		 //$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel');
		 return new Response($this->renderview('leerdoelen_eindtermen.html.twig', array('bronnen' => $bronnen, 'eindtermen' => $eindtermen)));
	 }

	/**
	* @Route("/view/context", name="view_context")
	*/
	public function contextOverzicht() {
		$c = $this->getDoctrine()->getRepository('AppBundle:Context')->findAll();
		return new Response($this->renderview('contexten.html.twig', array('contexten' => $c)));
	}

	/**
     * @Route("/admin/edit/context/{ctid}", name="admin_edit_context")
     */
    public function editContext(Request $request, $ctid)
    {
		try {
			$c = $this->getDoctrine()->getRepository('AppBundle:Context')->find($ctid);
			if(!isset($c)) {
				return $this->redirect($this->generateUrl("view_context"));
			}
			$form = $this->createForm(ContextType::class, $c);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($c);
				$em->flush();
				return $this->redirect($this->generateUrl("view_context"));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
		}
		catch (\Exception $bde) {
			return $this->redirect($this->generateUrl("view_context"));
		}
	}

	/**
	 * @Route("/admin/delete/confirm/{ctid}", name="admin_delete_confirm_context")
	 */
	public function deleteContext(Request $request, $ctid)
	{
		$c = $this->getDoctrine()->getRepository('AppBundle:Context')->find($ctid);
		return new Response(
			$this->renderview('deleteconfirmation.html.twig', 
			array('entity' => $c, 'confirmpath' => 'admin_delete_context')));
	}

	/**
	 * @Route("/admin/delete/context/{id}", name="admin_delete_context")
	 */
	public function delContextBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$c = $em->getRepository('AppBundle:Context')->find($id);
		$em->remove($c);
		$em->flush();
		return $this->redirect($this->generateUrl("view_context"));
	}


	/**
		* @Route("/admin/new/context", name="admin_new_context")
	*/
	public function nieuwContext(Request $request)
	{
		$ld = new Context();
		$form = $this->createForm(ContextType::class, $ld);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($ld);
			$em->flush();
			return $this->redirect($this->generateUrl("view_context"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
     * @Route("/admin/new/leerdoel", name="admin_new_leerdoel")
     */
    public function nieuwLeerdoel(Request $request)
    {
        $ld = new Leerdoel();
		$form = $this->createForm(LeerdoelType::class, $ld);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($ld);
            $em->flush();
			return $this->redirect($this->generateUrl("view_leerdoel"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("/admin/edit/leerdoel/{id}", name="admin_edit_leerdoel")
     * @Route("/admin/edit/leerdoel/{id}/return/course/{courseid}", name="admin_edit_leerdoel_return_course")
     */
    public function editLeerdoel(Request $request, $id, $courseid)
    {
		try {
			$ld = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->find($id);
			if(!isset($ld)) {
				return $this->redirect($this->generateUrl("view_leerdoel"));
			}
			$form = $this->createForm(LeerdoelType::class, $ld);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($ld);
				$em->flush();
				if(isset($courseid)) {
					return $this->redirect($this->generateUrl("view_course_leerdoelen", array("courseid" => $courseid)) . '#' . $ld->getToets()->getId());
				}
				return $this->redirect($this->generateUrl("leerdoel_perbron_bronid", array("bronid" => $ld->getBron()->getId())));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
		}
		catch (\Exception $bde) {
			return $this->redirect($this->generateUrl("view_leerdoel"));
		}
	}

	/**
     * @Route("/admin/new/leerdoel/course/{courseid}", name="admin_new_leerdoel_course")
     */
    public function editLeerdoelInCourse(Request $request, $courseid)
    {
  		$ld = new Leerdoel();
  		$course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($courseid);
  		if(isset($course))  {
  			$ld->setCourse($course);
  		} else {
  			return $this->redirect($this->generateUrl("course_leerdoelen"));
  		}
  		$form = $this->createForm(LeerdoelType::class, $ld);

  		$form->handleRequest($request);
  		if ($form->isSubmitted() && $form->isValid()) {
  			$em = $this->getDoctrine()->getManager();
              $em->persist($ld);
              $em->flush();
  			return $this->redirect($this->generateUrl("course_leerdoelen", array('courseid' => $courseid)));
  		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
     * @Route("/view/leerdoel/leeruitkomsten/{ldid}", name="view_leerdoel_leeruitkomsten")
     */
    public function leeruitkomstenInLeerdoel(Request $request, $ldid)
    {
			$ld = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->find($ldid);
			if(!isset($ld)) {
				return $this->redirect($this->generateUrl("view_leerdoel"));
			}
			//$uitkomsten = $ld->getLeeruitkomsten();
			return new Response($this->renderview('leerdoelmetuitkomst.html.twig', array('leerdoel' => $ld)));
	}

	/**
	 * @Route("/admin/delete/confirm/leerdoel/{id}", name="admin_delete_confirm_leerdoel")
	 */
	public function deleteLeerdoel(Request $request, $id)
	{
		$ld = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $ld, 'confirmpath' => 'admin_leerdoel_delete')));
	}

	/**
	 * @Route("/admin/delete/leerdoel/{id}", name="admin_delete_leerdoel")
	 */
	public function leerdoelVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$ld = $em->getRepository('AppBundle:Leerdoel')->find($id);
		if($ld->getBron() != null)
			$bronid = $ld->getBron()->getId();
		else
			$bronid = null;
		$leeruitkomsten = $ld->getLeeruitkomsten();
		foreach($leeruitkomsten as $lu) {
			$em->remove($lu);
		}
		$em->remove($ld);
		$em->flush();
		return $this->redirect($this->generateUrl("view_leerdoel_filter_bron", array("bronid" => $bronid)));
	}

}
?>
