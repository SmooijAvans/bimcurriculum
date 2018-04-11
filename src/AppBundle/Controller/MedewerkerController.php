<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\Type\MedewerkerType;
use AppBundle\Form\Type\MedewerkerPasswordType;

class MedewerkerController extends Controller
{

	 /**
     * @Route("/admin/view/medewerker", name="admin_view_medewerker")
     */
    public function medewerkerOverzicht(Request $request)
    {
		$medewerkers = $this->getDoctrine()->getRepository('AppBundle:Medewerker')->findBy(array(), array("volledigeNaam" => "ASC"));
		return new Response($this->renderview('medewerkers.html.twig', array('medewerkers' => $medewerkers)));
    }

	/**
     * @Route("/admin/new/medewerker", name="admin_new_medewerker")
     */
    public function nieuweMedewerker(Request $request)
    {
        $mw = new Medewerker();

		$form = $this->createForm(MedewerkerPasswordType::class, $mw);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
				$password = $this->get('security.password_encoder')
				->encodePassword($mw, $mw->getPlainPassword());
				$mw->setPassword($password);
			$em = $this->getDoctrine()->getManager();
            $em->persist($mw);
            $em->flush();
			return $this->redirect($this->generateUrl("admin_view_medewerker"));
		}
        return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
	 * @Route("admin/edit/medewerker/password/{id}", name="admin_edit_medewerker_password")
	 */
	public function editMedewerkerPw(Request $request, $id)
	{
		try {
			$mw = $this->getDoctrine()->getRepository('AppBundle:Medewerker')->find($id);
			if(!isset($mw)) {
				return $this->redirect($this->generateUrl("admin_view_medewerker"));
			}
			$form = $this->createForm(MedewerkerPasswordType::class, $mw);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				if($mw->getPlainPassword() != null) {
					$password = $this->get('security.password_encoder')
					->encodePassword($mw, $mw->getPlainPassword());
					$mw->setPassword($password);
				}
				$em = $this->getDoctrine()->getManager();
				$em->persist($mw);
				$em->flush();
				return $this->redirect($this->generateUrl("admin_view_medewerker"));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
		}
		catch (\Exception $bde) {
			//return new Response($bde->getMessage());
			return $this->redirect($this->generateUrl("admin_view_medewerker"));
		}
	}

	/**
     * @Route("/admin/edit/medewerker/{id}", name="admin_edit_medewerker")
     */
    public function editMedewerker(Request $request, $id)
    {
		try {
			$mw = $this->getDoctrine()->getRepository('AppBundle:Medewerker')->find($id);
			if(!isset($mw)) {
				return $this->redirect($this->generateUrl("admin_view_medewerker"));
			}
			$form = $this->createForm(MedewerkerType::class, $mw);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				if($mw->getPlainPassword() != null) {
					$password = $this->get('security.password_encoder')
					->encodePassword($mw, $mw->getPlainPassword());
					$mw->setPassword($password);
				}
				$em = $this->getDoctrine()->getManager();
				$em->persist($mw);
				$em->flush();
				return $this->redirect($this->generateUrl("admin_view_medewerker"));
			}
			return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
		}
		catch (\Exception $bde) {
			//return new Response($bde->getMessage());
			return $this->redirect($this->generateUrl("medewerker_overzicht"));
		}
	}

	/**
	 * @Route("/admin/delete/confirm/medewerker/{id}", name="admin_delete_confirm_medewerker")
	 */
	public function deleteMedewerker(Request $request, $id)
	{
		$mw = $this->getDoctrine()->getRepository('AppBundle:Medewerker')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $mw, 'confirmpath' => 'admin_delete_medewerker')));
	}

	/**
	 * @Route("/admin/delete/medewerker/{id}", name="admin_delete_medewerker")
	 */
	public function medewerkerVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$course = $em->getRepository('AppBundle:Medewerker')->find($id);
		$em->remove($course);
		$em->flush();
		return $this->redirect($this->generateUrl("view_medewerker"));
	}
}
?>
