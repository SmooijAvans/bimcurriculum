<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Leerdoel;
use AppBundle\Entity\Beroepsprofiel;
use AppBundle\Form\Type\BeroepsprofielType;

class BeroepsprofielController extends Controller
{

	/**
	* @Route("/view/beroepsprofiel", name="view_beroepsprofiel")
	*/
	public function beroepsprofielOverzicht(Request $request)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:Beroepsprofiel')->findAll();
		return new Response($this->renderview('beroepsprofielen.html.twig', array('beroepsprofielen' => $b)));
	}

	/**
	* @Route("/admin/new/beroepsprofiel", name="admin_new_beroepsprofiel")
	*/
	public function nieuwBeroepsprofiel(Request $request)
	{
		$b = new Beroepsprofiel();
		$form = $this->createForm(BeroepsprofielType::class, $b);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($b);
			$em->flush();
			return $this->redirect($this->generateUrl("view_beroepsprofiel"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	* @Route("admin/edit/beroepsprofiel/{id}", name="admin_edit_beroepsprofiel")
	*/
	public function editBeroepsprofiel(Request $request, $id)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:Beroepsprofiel')->find($id);
		if(!isset($b)) {
			return $this->redirect($this->generateUrl("view_beroepsprofiel"));
		}
		$form = $this->createForm(BeroepsprofielType::class, $b);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($b);
			$em->flush();
			return $this->redirect($this->generateUrl("view_beroepsprofiel"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	 * @Route("admin/delete/confirm/beroepsprofiel/{id}", name="admin_delete_confirm_beroepsprofiel")
	 */
	public function delBeroepsprofiel(Request $request, $id)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:Beroepsprofiel')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $b, 'confirmpath' => 'admin_delete_beroepsprofiel')));
	}

	/**
	 * @Route("admin/delete/beroepsprofiel/{id}", name="admin_delete_beroepsprofiel")
	 */
	public function beroepsprofielVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$b = $em->getRepository('AppBundle:Beroepsprofiel')->find($id);
		$em->remove($b);
		$em->flush();
		return $this->redirect($this->generateUrl("view_beroepsprofiel"));
	}

}
?>
