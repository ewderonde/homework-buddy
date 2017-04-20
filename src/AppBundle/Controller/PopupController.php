<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 16-2-2017
 * Time: 20:22
 */

namespace AppBundle\Controller;


use AppBundle\Controller\BaseController;
use AppBundle\Entity\Profile;
use AppBundle\Entity\Subject;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Entity\UserHasProfile;
use AppBundle\Form\SubjectGradeType;
use AppBundle\Form\SubjectType;
use AppBundle\Form\TaskType;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class PopupController extends BaseController
{

    public function taskAction(Task $task = null) {
        $action = 'create';
        if(empty($task)){
            $task = new Task();
            $task->setProfile($this->profile);
            $task->setCreator($this->user);

            $form = $this->formFactory->create(TaskType::class, $task, [
            ]);
        } else {
            $form = $this->formFactory->create(TaskType::class, $task, [
                'time_start' => $task->getTimeStart()->format('H:i')
            ]);

            $action = 'edit';
        }

        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Set manual date.
            if(!empty($form->get('date_raw')->getData())) {
                $date = new \DateTime($form->get('date_raw')->getData());
                $task->setDate($date);

                // Set manual timestart date.
                if(!empty($form->get('timeStart')->getData())) {
                    $date = new \DateTime($form->get('date_raw')->getData(). ' ' .$form->get('timeStart')->getData());
                    $task->setTimeStart($date);
                }
            }

            $this->em->persist($task);
            $this->em->flush();

            return new JsonResponse([
                'status' => 'success',
                'response' => ($action == 'create') ? 'Taak is aangemaakt!' : 'Taak is aangepast!',
                'redirect' => $this->router->generate('agenda_index')
            ]);

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return new JsonResponse([
                'status' => 'error',
                'response' => 'Er is iets fout gegaan'
            ]);
        }

        return new Response(
            $this->templating->render('popup/task_form.html.twig', array(
                'form' => $form->createView(),
            ))
        );
    }

    public function deleteTaskAction (Task $task = null) {
        if(empty($task)) {
            return new JsonResponse([
                'status' => 'error',
                'response' => 'Kan de gegeven taak niet vinden',
            ]);
        }

        $this->em->remove($task);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'success',
            'response' => 'De taak is verwijdert!',
            'redirect' => $this->router->generate('agenda_index')
        ]);
    }


    public function deleteUserHasProfileAction(UserHasProfile $uhp) {
        if(empty($uhp)) {
            return new JsonResponse([
                'status' => 'error',
                'response' => 'Kan het object niet vinden.  ',
            ]);
        }

        $this->em->remove($uhp);
        $this->em->flush();

        $data = array(
            'status' => 'success',
            'response' => 'De gebruiker heeft geen toegang meer tot dit profiel',
            'redirect' => $this->router->generate('profile_index')
        );

        return new JsonResponse($data);
    }


    public function subjectAction(Subject $subject = null) {
        $action = 'edit';
        if(empty($subject)){
            $subject = new Subject();
            $subject->setProfile($this->profile);

            $action = 'create';
        }

        $form = $this->formFactory->create(SubjectType::class, $subject, [
        ]);

        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {

            if($action == 'create') {
                $this->em->persist($subject);
            }

            $this->em->flush();

            return new JsonResponse([
                'status' => 'success',
                'response' => ($action == 'create') ? 'Vak is aangemaakt!' : 'Vak is aangepast!',
                'redirect' => $this->router->generate('subject_index')
            ]);

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return new JsonResponse([
                'status' => 'error',
                'response' => 'Er is iets fout gegaan'
            ]);
        }

        return new Response(
            $this->templating->render('popup/subject_form.html.twig', array(
                'form' => $form->createView(),
            ))
        );
    }

    public function deleteSubjectAction (Subject $subject = null) {
        if(empty($subject)) {
            return new JsonResponse([
                'status' => 'error',
                'response' => 'Kan het opgegeven vak niet vinden',
            ]);
        }

        $this->em->remove($subject);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'success',
            'response' => 'Het vak is verwijdert!',
            'redirect' => $this->router->generate('subject_index')
        ]);
    }

    public function subjectGradeAction(Subject $subject) {
        $form = $this->formFactory->create(SubjectGradeType::class, $subject, [
        ]);

        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {

            // get posted grades
            $grades = $form->get('grades')->getData();

            foreach($grades as $grade) {
                $grade->setSubject($subject);
                $this->em->persist($grade);
            }

            $this->em->flush();

            return new JsonResponse([
                'status' => 'success',
                'response' => 'Wijzigingen zijn opgeslagen!',
                'redirect' => $this->router->generate('grade_index')
            ]);

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            return new JsonResponse([
                'status' => 'error',
                'response' => 'Er is iets fout gegaan'
            ]);
        }

        return new Response(
            $this->templating->render('popup/subject_grade_form.html.twig', array(
                'form' => $form->createView(),
            ))
        );
    }

}