<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 19-4-2017
 * Time: 14:22
 */

namespace AppBundle\Controller;


use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Response;

class GradeController extends BaseController
{
    public function indexAction() {
        $subjectRepo = $this->em->getRepository('AppBundle:Subject');
        $subjectsRaw = $subjectRepo->getProfileSubjects($this->profile);

        $subjects = [];
        foreach($subjectsRaw as $subject) {
            $subjects[] = $subject->toArray();
        }

        return new Response($this->templating->render('grade/index.html.twig', [ 'title' => 'Cijfers', 'current_path' => 'grades', 'subjects' => $subjects ]));
    }
}