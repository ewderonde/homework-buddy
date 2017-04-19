<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 19-4-2017
 * Time: 13:45
 */

namespace AppBundle\Controller;




use Symfony\Component\HttpFoundation\Response;

class SubjectController extends BaseController
{
    public function indexAction() {
        $subjectRepo = $this->em->getRepository('AppBundle:Subject');
        $subjects = $subjectRepo->getProfileSubjects($this->profile);

        return new Response($this->templating->render('subject/index.html.twig', [ 'title' => 'Vakken', 'current_path' => 'subjects', 'subjects' => $subjects ]));
    }
}