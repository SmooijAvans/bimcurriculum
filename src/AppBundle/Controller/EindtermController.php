<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Eindterm;
use AppBundle\Entity\Beroepsprofiel;
use AppBundle\Form\Type\EindtermType;

class EindtermController extends Controller
{

	/**
	* @Route("/view/eindterm", name="view_eindterm")
	*/
	public function eindtermOverzicht(Request $request)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:Eindterm')->findAll();
		return new Response($this->renderview('eindtermen.html.twig', array('eindtermen' => $b)));
	}

	/**
	* @Route("/admin/new/eindterm", name="admin_new_eindterm")
	*/
	public function nieuweEindterm(Request $request)
	{
		$b = new Eindterm();
		$form = $this->createForm(EindtermType::class, $b);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($b);
			$em->flush();
			return $this->redirect($this->generateUrl("view_eindterm"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	* @Route("/admin/edit/eindterm/{id}", name="admin_edit_eindterm")
	*/
	public function editEindterm(Request $request, $id)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:Eindterm')->find($id);
		if(!isset($b)) {
			return $this->redirect($this->generateUrl("view_eindterm"));
		}
		$form = $this->createForm(EindtermType::class, $b);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($b);
			$em->flush();
			return $this->redirect($this->generateUrl("view_eindterm"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	 * @Route("/admin/delete/confirm/eindterm/{id}", name="admin_delete_confirm_eindterm")
	 */
	public function delEindterm(Request $request, $id)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:Eindterm')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $b, 'confirmpath' => 'admin_delete_eindterm')));
	}

	/**
	 * @Route("/admin/delete/eindterm/{id}", name="admin_delete_eindterm")
	 */
	public function eindtermVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$b = $em->getRepository('AppBundle:Eindterm')->find($id);
		$em->remove($b);
		$em->flush();
		return $this->redirect($this->generateUrl("view_eindterm"));
	}

}
?>
