<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Leeruitkomst;
use AppBundle\Form\Type\LeeruitkomstType;

class LeeruitkomstController extends Controller
{

	 /**
     * @Route("/view/leeruitkomst", name="view_leeruitkomst")
     */
    public function leeruitkomstOverzicht(Request $request)
    {
		$leeruitkomsten = $this->getDoctrine()->getRepository('AppBundle:Leeruitkomst')->findAll();
		return new Response($this->renderview('leeruitkomsten.html.twig', array('leeruitkomsten' => $leeruitkomsten)));
    }

	/**
     * @Route("/new/leeruitkomst", name="new_leeruitkomst")
     */
    public function nieuwLeeruitkomst(Request $request)
    {
        $lu = new Leeruitkomst();
		$form = $this->createForm(new LeeruitkomstType(-1), $lu);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($lu);
            $em->flush();
			return $this->redirect($this->generateUrl("view_leeruitkomst"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("/new/leeruitkomst/leerdoel/{leerdoelid}", name="new_leeruitkomst_leerdoel")
     */
    public function nieuwLeeruitkomstLeerdoel(Request $request, $leerdoelid)
    {
        $lu = new Leeruitkomst();
		$ld = $this->getDoctrine()->getRepository('AppBundle:Leerdoel')->find($leerdoelid);
		if(isset($ld))  {
			$lu->setLeerdoel($ld);
		}

		$form = $this->createForm(new LeeruitkomstType($lu->getLeerdoel()->getId()), $lu);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($lu);
            $em->flush();
			return $this->redirect($this->generateUrl("view_leerdoel_leeruitkomsten", array('ldid' => $ld->getId())));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("/edit/leeruitkomst/{id}", name="edit_leeruitkomst")
     */
    public function editLeeruitkomst(Request $request, $id)
    {
			$lu = $this->getDoctrine()->getRepository('AppBundle:Leeruitkomst')->find($id);
			if(!isset($lu)) {
				return $this->redirect($this->generateUrl("view_leeruitkomst"));
			}
			$form = $this->createForm(new LeeruitkomstType($lu->getLeerdoel()->getId()), $lu);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($lu);
				$em->flush();
				return $this->redirect($this->generateUrl("view_leerdoel_leeruitkomsten", array('ldid' => $lu->getLeerdoel()->getId())));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	 * @Route("/delete/confirm/leeruitkomst/{id}", name="delete_confirm_leeruitkomst")
	 */
	public function luVerwijderen(Request $request, $id)
	{
		$leeruitkomst = $this->getDoctrine()->getRepository('AppBundle:Leeruitkomst')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $leeruitkomst, 'confirmpath' => 'delete_leeruitkomst')));
	}

	/**
	 * @Route("/delete/leeruitkomst/{id}", name="delete_leeruitkomst")
	 */
	public function luVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$leeruitkomst = $em->getRepository('AppBundle:Leeruitkomst')->find($id);
		$em->remove($leeruitkomst);
		$em->flush();
		return $this->redirect($this->generateUrl("view_leeruitkomst"));
	}

	/**
	 * @Route("/delete/confirm/leeruitkomst/leerdoel/{id}", name="delete_confirm_leeruitkomst_leerdoel")
	 */
	public function ldLuVerwijderen(Request $request, $id)
	{
		$leeruitkomst = $this->getDoctrine()->getRepository('AppBundle:Leeruitkomst')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $leeruitkomst, 'confirmpath' => 'delete_leeruitkomst_leerdoel')));
	}

	/**
	 * @Route("/delete/leeruitkomst/leerdoel/{id}", name="delete_leeruitkomst_leerdoel")
	 */
	public function ldLuVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$leeruitkomst = $em->getRepository('AppBundle:Leeruitkomst')->find($id);
		$em->remove($leeruitkomst);
		$em->flush();
		return $this->redirect($this->generateUrl('view_leerdoel_leeruitkomsten', array('ldid' => $leeruitkomst->getLeerdoel()->getId())));
	}
}
?>
