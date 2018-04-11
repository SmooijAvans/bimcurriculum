<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Literatuur;
use AppBundle\Form\Type\LiteratuurType;

class LiteratuurController extends Controller
{

	 /**
     * @Route("/view/literatuur", name="view_literatuur")

     */
    public function literatuurOverzicht(Request $request)
    {
  		$literatuur = $this->getDoctrine()->getRepository('AppBundle:Literatuur')->findBy(array(), array("auteur" => "ASC"));
  		return new Response($this->renderview('literatuur.html.twig', array('literatuur' => $literatuur)));
    }

    /**
     * @Route("/delete/confirm/literatuur/{id}", name="delete_confirm_literatuur")
     */
    public function deleteLiteratuur(Request $request, $id)
    {
      $l = $this->getDoctrine()->getRepository('AppBundle:Literatuur')->find($id);
      return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $l, 'confirmpath' => 'delete_literatuur')));
    }

    /**
     * @Route("/new/literatuur", name="new_literatuur")
    */
    public function nieuweLiteratuur(Request $request)
    {
      $l = new Literatuur();
      $em = $this->getDoctrine()->getManager();
      $form = $this->createForm(LiteratuurType::class, $l);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($l);
        $em->flush();
        return $this->redirect($this->generateUrl("view_literatuur"));
      }
      return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

    /**
     * @Route("/edit/literatuur/{id}", name="edit_literatuur")
    */
    public function editLiteratuur(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
  		$l = $this->getDoctrine()->getRepository('AppBundle:Literatuur')->findOneById($id);
  		if(!isset($l)) {
  			return $this->redirect($this->generateUrl("view_literatuur"));
  		}
      $form = $this->createForm(LiteratuurType::class, $l);
  		$form->handleRequest($request);
  		if ($form->isSubmitted() && $form->isValid()) {
  			$em->persist($l);
  			$em->flush();
  			return $this->redirect($this->generateUrl("view_literatuur"));
  		}
  		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
	 * @Route("/delete/literatuur/{id}", name="delete_literatuur")
	 */
	public function literatuurVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$l = $em->getRepository('AppBundle:Literatuur')->find($id);
		$em->remove($l);
		$em->flush();
		return $this->redirect($this->generateUrl("view_literatuur"));
	}
}
?>
