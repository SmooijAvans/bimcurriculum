<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Domein;
use AppBundle\Form\Type\DomeinType;

class DomeinController extends Controller
{

	 /**
     * @Route("/view/domein", name="view_domein")
     */
    public function llOverzicht(Request $request)
    {
		$domeinen = $this->getDoctrine()->getRepository('AppBundle:Domein')->findAll();
		return new Response($this->renderview('domeinen.html.twig', array('domeinen' => $domeinen)));
    }

    /**
      * @Route("/view/domein/toetsen", name="view_domein_toetsen")
      */
     public function getDomeinToetsen(Request $request)
     {
		$domeinen = $this->getDoctrine()->getRepository('AppBundle:Domein')->findAll();
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findBy(array(), array('jaar'=>'asc', 'periode' => 'asc'));

		return new Response($this->renderview('domeintoetsen.html.twig', array('domeinen' => $domeinen, 'courses' => $courses)));
     }


	/**
     * @Route("/admin/new/domein", name="admin_new_domein")
     */
    public function nieuwDomein(Request $request)
    {
        $ll = new Domein();
		$form = $this->createForm(DomeinType::class, $ll);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $em->persist($ll);
            $em->flush();
			return $this->redirect($this->generateUrl("view_domein"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
     * @Route("/admin/edit/domein/{id}", name="admin_edit_domein")
     */
    public function editll(Request $request, $id)
    {
		$ll = $this->getDoctrine()->getRepository('AppBundle:Domein')->find($id);
		if(!isset($ll)) {
			return $this->redirect($this->generateUrl("view_domein"));
		}
		$form = $this->createForm(DomeinType::class, $ll);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($ll);
			$em->flush();
			return $this->redirect($this->generateUrl("view_domein"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}


	/**
	 * @Route("/admin/delete/confirm/domein/{id}", name="admin_delete_confirm_domein")
	 */
	public function deleteLl(Request $request, $id)
	{
		$ll = $this->getDoctrine()->getRepository('AppBundle:Domein')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $ll, 'confirmpath' => 'admin_delete_domein')));
	}

	/**
	 * @Route("/admin/delete/domein/{id}", name="admin_delete_domein")
	 */
	public function llVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$ll = $em->getRepository('AppBundle:Domein')->find($id);
		$em->remove($ll);
		$em->flush();
		return $this->redirect($this->generateUrl("view_domein"));
	}
}
?>
