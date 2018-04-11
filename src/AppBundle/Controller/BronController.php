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

class BronController extends Controller
{

	 /**
     * @Route("/view/bron", name="view_bron")
     */
    public function bronOverzicht(Request $request)
    {
		$bronnen = $this->getDoctrine()->getRepository('AppBundle:Bron')->findAll();
		return new Response($this->renderview('bronnen.html.twig', array('bronnen' => $bronnen)));
    }

	/**
     * @Route("/view/bronherkomst", name="view_bronherkomst")
     */
    public function bronHerkomstOverzicht(Request $request)
    {
		$bronherkomsten = $this->getDoctrine()->getRepository('AppBundle:BronHerkomst')->findAll();
		return new Response($this->renderview('bronherkomsten.html.twig', array('bronherkomsten' => $bronherkomsten)));
    }

	/**
     * @Route("/admin/new/bron", name="admin_new_bron")
     */
    public function nieuwbron(Request $request)
    {
        $b = new bron();
		$form = $this->createForm(BronType::class, $b);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($b);
            $em->flush();
			return $this->redirect($this->generateUrl("view_bron"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("/admin/new/bronherkomst", name="admin_new_bronherkomst")
     */
    public function nieuwBronHerkomst(Request $request)
    {
        $bh = new BronHerkomst();
		$form = $this->createForm(BronHerkomstType::class, $bh);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($bh);
            $em->flush();
			return $this->redirect($this->generateUrl("view_bronherkomst"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("admin/edit/bron/{id}", name="admin_edit_bron")
     */
    public function editbron(Request $request, $id)
    {
		//try {
			$b = $this->getDoctrine()->getRepository('AppBundle:Bron')->find($id);
			if(!isset($b)) {
				return $this->redirect($this->generateUrl("view_bron"));
			}
			$form = $this->createForm(bronType::class, $b);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($b);
				$em->flush();
				return $this->redirect($this->generateUrl("view_bron"));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
		//}
		//catch (\Exception $bde) {
			//return $this->redirect($this->generateUrl("bron_overzicht"));
		//}
	}

	/**
	 * @Route("admin/edit/bronherkomst/{id}", name="admin_edit_bronherkomst")
	 */
	public function editbronherkomst(Request $request, $id)
	{
		$b = $this->getDoctrine()->getRepository('AppBundle:BronHerkomst')->find($id);
		if(!isset($b)) {
			return $this->redirect($this->generateUrl("view_bronherkomst"));
		}
		$form = $this->createForm(BronHerkomstType::class, $b);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($b);
			$em->flush();
			return $this->redirect($this->generateUrl("view_bronherkomst"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	 * @Route("admin/delete/confirm/bron/{id}", name="admin_delete_confirm_bron")
	 */
	public function deleteBron(Request $request, $id)
	{
		$bron = $this->getDoctrine()->getRepository('AppBundle:Bron')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $bron, 'confirmpath' => 'admin_delete_bron')));
	}

	/**
	 * @Route("/admin/delete/bron/{id}", name="admin_delete_bron")
	 */
	public function bronVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$bron = $em->getRepository('AppBundle:Bron')->find($id);
		$em->remove($bron);
		$em->flush();
		return $this->redirect($this->generateUrl("view_bron"));
	}

	/**
	 * @Route("/admin/delete/confirm/bronherkomst/{id}", name="admin_delete_confirm_bronherkomst")
	 */
	public function deleteBronHk(Request $request, $id)
	{
		$bh = $this->getDoctrine()->getRepository('AppBundle:BronHerkomst')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $bh, 'confirmpath' => 'admin_delete_bronherkomst')));
	}

	/**
	 * @Route("/admin/delete/bronherkomst/{id}", name="admin_delete_bronherkomst")
	 */
	public function bronHkVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$bh = $em->getRepository('AppBundle:BronHerkomst')->find($id);
		$em->remove($bh);
		$em->flush();
		return $this->redirect($this->generateUrl("view_bronherkomst"));
	}
}
?>
