<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Toets;
use AppBundle\Form\Type\ToetsType;
use AppBundle\Form\Type\ToetsSoortType;
use AppBundle\Entity\ToetsSoort;
use AppBundle\Entity\Toetsstof;
use AppBundle\Entity\Literatuur;
use AppBundle\Entity\Medewerker;
use AppBundle\Entity\ArchiefToets;
use AppBundle\Entity\Verbeteractie;
use AppBundle\Form\Type\VerbeteractieType;
use AppBundle\Form\Type\ToetsstofType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPExcel;
use PHPExcel_IOFactory;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ToetsController extends Controller
{

	/**
	* @Route("/view/toets", name="view_toets")
	* @Route("/view/toets/{courseid}", name="view_toets")
	*/
	public function toetsOverzicht(Request $request, $courseid = null)
	{
		if($courseid == null)
			$toetsen = $this->getDoctrine()->getRepository('AppBundle:Toets')->findBy(array(), array('code'=>'asc'));
		else
			$toetsen = $this->getDoctrine()->getRepository('AppBundle:Toets')->findBy(array("course" => $courseid), array('code'=>'asc'));
		$courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
		return new Response($this->renderview('toetsen.html.twig', array('toetsen' => $toetsen, 'courses' => $courses)));
	}

	/**
     * @Route("/new/verbeteractie/{tid}", name="new_verbeteractie")
     */
    public function nieuweVerbeteractie(Request $request, $tid)
    {
    	$toets = $this->getDoctrine()->getRepository('AppBundle:Toets')->findOneById($tid);
    	if($toets == null) {
    		//ga terug
    		return $this->redirect($this->generateUrl("view_course"));
    	}
		$a = new Verbeteractie();
		$a->setToets($toets);
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(VerbeteractieType::class, $a);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($a);
			$em->flush();
			return $this->redirect($this->generateUrl("view_course_leerdoelen", array("courseid" => $toets->getCourse()->getId())));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
	* @Route("/view/toets/modulewijzer/{toetsid}", name="view_toets_modulewijzer")
	*/
	public function toetsModulewijzer(Request $request, $toetsid = null) 
	{
		$toets = $this->getDoctrine()->getRepository('AppBundle:Toets')->find($toetsid);

		$phpw = new PhpWord();
		$document = $phpw->loadTemplate('./phpoffice_templates/t_modulehandleiding_bim.docx');
		$document->setValue("toetsnaam", htmlspecialchars($toets->getNaam()));
		$document->setValue("toetscode", htmlspecialchars($toets->getCode()));
		$document->setValue("jaar", "Jaar " . ($toets->getCourse() != null ? $toets->getCourse()->getJaar(): ""));
		$document->setValue("periode", "Periode " .  ($toets->getCourse() != null ? $toets->getCourse()->getPeriode() : ""));
		$document->setValue("variant",  ($toets->getCourse() != null ? ($toets->getCourse()->getVoltijddeeltijd() == "V" ? "Voltijd" : "") : ""));
		$document->setValue("ec", $toets->getEc()."EC");
		$document->setValue("verantwoordelijke", htmlspecialchars(($toets->getVerantwoordelijke() != null) ? $toets->getVerantwoordelijke()->getVolledigeNaam() : ""));
		$document->setValue("reviewer", htmlspecialchars((($toets->getReviewer() != null) ? $toets->getReviewer()->getVolledigeNaam() : "")));
		$document->setValue("vandaag", date('d-m-y'));
		$document->setValue("ecomvang", $toets->getEc()."EC('s)");
		$document->setValue("toetsvorm", htmlspecialchars(($toets->getSoort() != null ? $toets->getSoort()->getNaam() : "")));
		$document->setValue("resultaatschaal", $toets->getResultaatschaal());
		$document->setValue("voorkennis", $toets->getVoorkennis());
		$document->setValue("hulpmiddelen", $toets->getHulpmiddelen());
		$document->setValue("minimale_eis", $toets->getMinimaleEis());
		$document->setValue("uren", ($toets->getEc() * 28) . " uren");

		//leerdoelen die horen bij de Toets
		$lds = $toets->getLeerdoelen();
		$ldstxt = "";
		foreach ($lds as $ld) {
			//open office xml format:
			//<run><runproperties></runproperties><inhoud>...</inhoud></run>
			//run = ?, properties = b.v. dikgedrukt, inhoud = b.v. tekst (w:t)
			$ldstxt .= '<w:r><w:rPr><w:b/></w:rPr><w:t>' . htmlspecialchars($ld->getBeschrijving()) . "</w:t></w:r><w:br/>"; //slordig, maar werkt als enter (word versie van \n)
			$leeruitkomsten = $ld->getLeeruitkomsten();
			foreach ($leeruitkomsten as $lu) {
				$ldstxt .= '<w:r><w:rPr></w:rPr><w:t>- '. htmlspecialchars($lu->getBeschrijving()) . "</w:t></w:r><w:br/>";
			}
			$ldstxt .= "<w:br/>";
		}
		$document->setValue("leerdoelen", $ldstxt);

		//toetsstof
		$stoftxt = "";
		$stofitems = $toets->getToetsstof();
		foreach($stofitems as $stof) {
			$stoftxt .= $stof->getLiteratuur()->getAuteur() . ": " . $stof->getLiteratuur()->getTitel() . " - " . $stof->getLiteratuur()->getIdentificatie() . " (" . $stof->getStof() . ")" . "<w:br/>";
		}
		$document->setValue("toetsstof", $stoftxt);

		$documentName = $toets->getCode() . "_" . $toets->getNaam() . '_dd' . date('ymd').'.docx';
		$temp_file = tempnam(sys_get_temp_dir(), $documentName);
		$document->saveAs($temp_file);

		// Generate response
		$response = new BinaryFileResponse($temp_file);
		$response->setContentDisposition(
		  ResponseHeaderBag::DISPOSITION_ATTACHMENT,
		  $documentName
		);

		return $response;
	}

	/**
	* @Route("/view/toets/toetsmatrijs/{toetsid}", name="view_toets_toetsmatrijs")
	*/
	public function toetsToetsmatrijs(Request $request, $toetsid = null) {
		$toets = $this->getDoctrine()->getRepository('AppBundle:Toets')->find($toetsid);
		$leerdoelen = $toets->getLeerdoelen();

		$phpe = new PHPExcel();

		$document = PHPExcel_IOFactory::load('./phpoffice_templates/toetsmatrijs_BIM.xlsx');
		$document->setActiveSheetIndex(0);
		$document->getActiveSheet()->setCellValue('A1', 'Toetsmatrijs ' . $toets->getCode() . " (" . $toets->getNaam() . ")");

		//leerdoelen op hun plek zetten
		$startRow = 4;
		foreach($leerdoelen as $leerdoel) {
			$document->getActiveSheet()->setCellValue('A' . $startRow, $leerdoel->getBeschrijving());
			if($leerdoel->getToetspercentage() != NULL) {
				$document->getActiveSheet()->setCellValue('B' . $startRow, ($leerdoel->getToetspercentage()/100));
			}
			$bloom = $leerdoel->getBloomniveau();
			if($bloom < 3)
				$document->getActiveSheet()->setCellValue('C' . $startRow, 'x');
			else if($bloom == 3)
				$document->getActiveSheet()->setCellValue('D' . $startRow, 'x');
			else if($bloom > 3)
				$document->getActiveSheet()->setCellValue('E' . $startRow, 'x');
			$startRow++;
		}

		//opslaan en terugzenden
		$documentName = "matrijs_" . $toets->getCode() . "_" . $toets->getNaam() . '_dd' . date('ymd').'.xlsx';
		$temp_file = tempnam(sys_get_temp_dir(), $documentName);
		//$document->saveAs('bundles/app/phpoffice_generated/' . $documentName);
		$fileWriter = PHPExcel_IOFactory::createWriter($document, 'Excel2007');
		$fileWriter->save($temp_file);

		// Generate response
		//$response = new Response();
		$response = new BinaryFileResponse($temp_file);
		$response->setContentDisposition(
		  ResponseHeaderBag::DISPOSITION_ATTACHMENT,
		  $documentName
		);

		return $response;
	}

	/**
	* @Route("/view/toets/toetsoverzicht/{toetsid}", name="view_toets_toetsoverzicht")
	*/
	public function toetsToetsoverzicht(Request $request, $toetsid = null) {
		$toets = $this->getDoctrine()->getRepository('AppBundle:Toets')->find($toetsid);

		$phpw = new PhpWord();
		$document = $phpw->loadTemplate('./phpoffice_templates/t_toetsoverzicht_bim.docx');
		$document->setValue("coursenaam", htmlspecialchars($toets->getCourse()->getNaam()));
		$document->setValue("toetscode", htmlspecialchars($toets->getCode()));
		$document->setValue("jaar", $toets->getCourse()->getJaar());
		$document->setValue("periode", $toets->getCourse()->getPeriode());
		$document->setValue("domein", $toets->getDomein()->getNaam());

		$niveau = 0;
		$leerdoelen = $toets->getLeerdoelen();
		foreach($leerdoelen as $leerdoel) {
			$context = $leerdoel->getContext();
			if($context != null) {
				if($context->getNiveau() > $niveau)
					$niveau = $context->getNiveau();
			}
		}

		$document->setValue("context", $niveau > 0 ? $niveau: "niet bekend");
		$document->setValue("ec", $toets->getEc());
		$document->setValue("ecinuren", $toets->getEc() * 28);
		$document->setValue("verantwoordelijke", htmlspecialchars(($toets->getVerantwoordelijke() != null) ? $toets->getVerantwoordelijke()->getVolledigeNaam() : ""));
		$document->setValue("reviewer", htmlspecialchars((($toets->getReviewer() != null) ? $toets->getReviewer()->getVolledigeNaam() : "")));
		$document->setValue("voorkennis", htmlspecialchars($toets->getVoorkennis()));
		$document->setValue("soort", htmlspecialchars(($toets->getSoort() != null ? $toets->getSoort()->getNaam() : "")));
		$document->setValue("duurinminuten", ($toets->getDuurinminuten() > 0) ? $toets->getDuurinminuten() . " minuten" : "");
		$document->setValue("resultaatschaal", $toets->getResultaatschaal());
		$document->setValue("cesuur", $toets->getMinimaleEis());
		$document->setValue("hulpmiddelen", $toets->getHulpmiddelen());

		//leerdoelen die horen bij de Toets
		$lds = $toets->getLeerdoelen();
		$ldstxt = "";
		foreach ($lds as $ld) {
			//open office xml format:
			//<run><runproperties></runproperties><inhoud>...</inhoud></run>
			//run = ?, properties = b.v. dikgedrukt, inhoud = b.v. tekst (w:t)
			$ldstxt .= '<w:r><w:rPr><w:b/></w:rPr><w:t>' . htmlspecialchars($ld->getBeschrijving()) . "</w:t></w:r><w:br/>"; //slordig, maar werkt als enter (word versie van \n)
			$leeruitkomsten = $ld->getLeeruitkomsten();
			foreach ($leeruitkomsten as $lu) {
				$ldstxt .= '<w:r><w:rPr></w:rPr><w:t>- '. htmlspecialchars($lu->getBeschrijving()) . "</w:t></w:r><w:br/>";
			}
			$ldstxt .= "<w:br/>";
		}
		$document->setValue("leerdoelen", $ldstxt);

		//toetsstof
		$stoftxt = "";
		$stofitems = $toets->getToetsstof();
		foreach($stofitems as $stof) {
			$stoftxt .= $stof->getLiteratuur()->getAuteur() . ": " . $stof->getLiteratuur()->getTitel() . " - " . $stof->getLiteratuur()->getIdentificatie() . " (" . $stof->getStof() . ")" . "<w:br/>";
		}
		$document->setValue("literatuur", $stoftxt);

		$documentName = "toetsoverzicht_" . $toets->getCode() . "_" . $toets->getNaam() . '_dd' . date('ymd').'.docx';
		$temp_file = tempnam(sys_get_temp_dir(), $documentName);
		//$document->saveAs('bundles/app/phpoffice_generated/' . $documentName);
		$document->saveAs($temp_file);

		// Generate response
		//$response = new Response();
		$response = new BinaryFileResponse($temp_file);
		$response->setContentDisposition(
		  ResponseHeaderBag::DISPOSITION_ATTACHMENT,
		  $documentName
		);

		return $response;
	}

	/**
	 * @Route("/view/toetssoort", name="view_toetssoort")
	 */
	public function toetsSoortOverzicht(Request $request)
	{
		$toetsSoorten = $this->getDoctrine()->getRepository('AppBundle:ToetsSoort')->findBy(array(), array('naam'=>'asc'));
		return new Response($this->renderview('toetssoorten.html.twig', array('toetssoorten' => $toetsSoorten)));
	}

	/**
     * @Route("/admin/new/toets", name="admin_new_toets")
     */
    public function nieuweToets(Request $request)
    {
		$t = new Toets();
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(ToetsType::class, $t);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($t);
			$em->flush();
			return $this->redirect($this->generateUrl("view_toets"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
    }

	/**
	* @Route("/admin/new/toets/versie/{tid}", name="admin_new_toets_versie")
	*/
	public function nieuweVersieToets(Request $request, $tid)
	{
		$nieuwetoets = $this->getDoctrine()->getRepository('AppBundle:Toets')->findOneById($tid);
		$oudetoets = $this->getDoctrine()->getRepository('AppBundle:Toets')->findOneById($tid);
		$at = new ArchiefToets($oudetoets);
		$at->setToets($oudetoets);
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(ToetsType::class, $nieuwetoets);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$at->setReden($request->request->get("reden"));
			$em->persist($nieuwetoets);
			$em->persist($at);
			$em->flush();
			return $this->redirect($this->generateUrl("view_toets"));
		}
		return new Response($this->renderview('form_archieftoets.html.twig', array('form' => $form->createView(), 'oldid' => $oudetoets->getId())));
	}

	/**
	 * @Route("/view/archieftoets/toets/{tid}", name="view_archieftoets_toets")
	 */
	public function toonArchieftoetsen(Request $request, $tid)
	{
		$toets = $this->getDoctrine()->getRepository('AppBundle:Toets')->findOneById($tid);
		return new Response($this->renderview('archieftoetsen.html.twig', array('toets' => $toets)));
	}

	/**
	 * @Route("/admin/new/toetssoort", name="admin_new_toetssoort")
	 */
	public function nieuweToetsSoort(Request $request)
	{
		$t = new ToetsSoort();
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(ToetsSoortType::class, $t);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($t);
			$em->flush();
			return $this->redirect($this->generateUrl("view_toetssoort"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	* @Route("/admin/edit/toets/{id}", name="admin_edit_toets")
	*/
	public function editToets(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$t = $this->getDoctrine()->getRepository('AppBundle:Toets')->findOneById($id);
		if(!isset($t)) {
			return $this->redirect($this->generateUrl("view_toets"));
		}
		$form = $this->createForm(ToetsType::class, $t);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$em->persist($t);
			$em->flush();

			$course = $t->getCourse()->getId();
			if(isset($course))
				return $this->redirect($this->generateUrl("view_toets", array("courseid" => $course)));
			else
				return $this->redirect($this->generateUrl("view_toets"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
	 * @Route("/admin/edit/toetssoort/{id}", name="admin_edit_toetssoort")
	 */
	public function editToetsSoort(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$t = $this->getDoctrine()->getRepository('AppBundle:ToetsSoort')->findOneById($id);
		if(!isset($t)) {
			return $this->redirect($this->generateUrl("view_toetssoort"));
		}
		$form = $this->createForm(ToetsSoortType::class, $t);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			//omdat doctrine niet helemaal netjes werkt, handmatig de toets keys eerst op null zetten (regel 69 set ze met de nieuwe waarden)

			$em->persist($t);
			$em->flush();
			return $this->redirect($this->generateUrl("view_toetssoort"));
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
	}

	/**
     * @Route("/view/toets/course/{id}", name="view_toets_course")
     */
    public function toetsenPerCourse(Request $request, $id)
    {
		$toetsen = Array();
		$em = $this->getDoctrine()->getManager();
		$course = $em->getRepository('AppBundle:Course')->find($id);
		$leerdoelen = $course->getLeerdoelen();
		foreach($leerdoelen as $leerdoel) {
			$leeruitkomsten = $leerdoel->getLeeruitkomsten();
			foreach($leeruitkomsten as $uitkomst) {
				$toetsen[] = $uitkomst->getToets();
			}
		}
		$toetsen_distinct = array_unique($toetsen);
		foreach($toetsen_distinct as $toets) {
			echo $toets;
		}
	}

  /**
   * @Route("/new/toetsstof/{id}", name="new_toetsstof")
  */
  public function nieuweToetsstof(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
    $toets = $em->getRepository('AppBundle:Toets')->find($id);
    $toetsstof = new Toetsstof();
    $toetsstof->setToets($toets);

    $form = $this->createForm(ToetsstofType::class, $toetsstof);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($toetsstof);
			$em->flush();
			return $this->overzichtToetsstof($request, $id); //terug naar overzicht
		}
		return new Response($this->renderview('form.html.twig', array('form' => $form->createView())));
  }

	/**
	* @Route("/view/toetsstof/{id}", name="view_toetsstof")
	*/
	public function overzichtToetsstof(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$toets = $em->getRepository('AppBundle:Toets')->find($id);
    	$toetsstof = $em->getRepository('AppBundle:Toetsstof')->findBy(array(
			'toets' => $id,
		));
		return new Response($this->renderview('toetsstof.html.twig', array('toetsstof' => $toetsstof, 'toetsid' => $toets->getId(), 'courseid' => $toets->getCourse()->getId())));
	}

	/**
	 * @Route("/delete/confirm/toetsstof/{toetsid}/{literatuurid}", name="delete_confirm_toetsstof")
	 */
	public function deleteToetsstof(Request $request, $toetsid, $literatuurid)
	{
		$toetsstof = $this->getDoctrine()->getRepository('AppBundle:Toetsstof')->findOneBy(array('toets' => $toetsid, 'literatuur' => $literatuurid));
		return new Response($this->renderview('deletetoetsstofconfirmation.html.twig', array('entity' => $toetsstof, 'confirmpath' => 'toetsstof_delete_confirmed')));
	}

	/**
	 * @Route("/delete/toetsstof/{toetsid}/{literatuurid}", name="delete_toetsstof")
	 */
	public function toetsstofVerwijderenBevestigd(Request $request, $toetsid, $literatuurid)
	{
		$em = $this->getDoctrine()->getManager();
		$toetsstof = $em->getRepository('AppBundle:Toetsstof')->findOneBy(array('toets' => $toetsid, 'literatuur' => $literatuurid));
		$em->remove($toetsstof);
		$em->flush();
		return $this->overzichtToetsstof($request, $toetsid);
	}

	/**
	 * @Route("/admin/delete/confirm/toets/{id}", name="admin_delete_confirm_toets")
	 */
	public function deleteToets(Request $request, $id)
	{
		$toets = $this->getDoctrine()->getRepository('AppBundle:Toets')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $toets, 'confirmpath' => 'admin_delete_toets')));
	}

	/**
	 * @Route("/admin/delete/confirm/toetssoort/{id}", name="admin_delete_confirm_toetssoort")
	 */
	public function deleteToetsSoort(Request $request, $id)
	{
		$toetssoort = $this->getDoctrine()->getRepository('AppBundle:ToetsSoort')->find($id);
		return new Response($this->renderview('deleteconfirmation.html.twig', array('entity' => $toetssoort, 'confirmpath' => 'toetssoort_delete_confirmed')));
	}

	/**
	 * @Route("/admin/delete/toets/{id}", name="admin_delete_toets")
	 */
	public function toetsVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$toets = $em->getRepository('AppBundle:Toets')->find($id);
		$em->remove($toets);
		$em->flush();
		return $this->redirect($this->generateUrl("view_toets"));
	}

	/**
	 * @Route("/admin/delete/toetssoort/{id}", name="admin_delete_toetssoort")
	 */
	public function toetssoortVerwijderenBevestigd(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$toetssoort = $em->getRepository('AppBundle:ToetsSoort')->find($id);
		$em->remove($toetssoort);
		$em->flush();
		return $this->redirect($this->generateUrl("view_toetssoort"));
	}
}
?>
