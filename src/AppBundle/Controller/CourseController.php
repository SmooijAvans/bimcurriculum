<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Course;
use AppBundle\Form\Type\CourseType;
use AppBundle\Form\Type\Leerdoel;
use Doctrine\Common\Collections\ArrayCollection;

class CourseController extends Controller
{

	 /**
     * @Route("/view/course", name="view_course")
     */
    public function courseOverzicht(Request $request)
    {
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findBy(array(), array("naam" => "ASC"));
		return new Response($this->renderview('courses.html.twig', array('courses' => $courses)));
    }

    //TO DO: REFACTORING

	/**
	* @Route("admin/course/{id}", name="admin_course")
	*/
	public function adminCourseEdit(Request $request, $id)
	{
		$course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($id);
		$leerdoelen = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->findByCourse($id);
		$bronnen = $this->getDoctrine()->getRepository('AppBundle:Bron')->findAll();
		return new Response($this->renderview('courses_admin.html.twig', array('course' => $course, 'leerdoelen' => $leerdoelen, 'bronnen' => $bronnen)));
	}

	/**
	* @Route("admin/courses", name="admin_courses")
	*/
	public function adminCourses(Request $request)
	{
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findBy(array(), array("naam" => "ASC"));
		return new Response($this->renderview('courses_admin_all.html.twig', array('courses' => $courses)));
	}

	//END TO DO

	 /**
     * @Route("admin/delete/confirm/course/{id}", name="admin_delete_conform_course")
     */
    public function courseVerwijderen(Request $request, $id)
    {
		$course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $course, 'confirmpath' => 'admin_delete_course')));
    }

	 /**
     * @Route("admin/delete/course/{id}", name="admin_delete_course")
     */
    public function courseVerwijderenBevestigd(Request $request, $id)
    {
		$em = $this->getDoctrine()->getManager();
		$course = $em->getRepository('AppBundle:Course')->find($id);
		$em->remove($course);
		$em->flush();
		return $this->redirect($this->generateUrl("view_course"));
	}

	/**
     * @Route("admin/new/course", name="admin_new_course")
     */
    public function nieuwCourse(Request $request)
    {
        $c = new course();
		$form = $this->createForm(CourseType::class, $c);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
			return $this->redirect($this->generateUrl("view_course"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("admin/edit/course/{id}", name="admin_edit_course")
     */
    public function editCourse(Request $request, $id)
    {
		try {
			$c = $this->getDoctrine()->getRepository('AppBundle:Course')->find($id);
			if(!isset($c)) {
				return $this->redirect($this->generateUrl("view_course"));
			}
			$form = $this->createForm(courseType::class, $c);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($c);
				$em->flush();
				return $this->redirect($this->generateUrl("view_course"));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
		}
		catch (\Exception $bde) {
			return new Response($bde->getMessage());
		}
	}

	/**
	* @Route("/view/course/{courseid}/leerdoelen", name="view_course_leerdoelen")
	* @Route("/view/course/{courseid}/leerdoelen/filter/bron/{bronid}", name="view_course_leerdoelen_filter_bron")
	*/
	public function getCourseLeerdoelen(Request $request, $courseid = NULL, $bronid = NULL) {
		$course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($courseid);
		return new Response($this->renderview('coursesmetleerdoelentoets.html.twig', array('course' => $course)));
	}
}
?>
