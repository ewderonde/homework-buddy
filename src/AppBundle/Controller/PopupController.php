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
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Entity\UserHasProfile;
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

//    public function userHasProfileAction() {
//        $userHasProfile = new UserHasProfile();
//        $form = $this->formFactory->create(TaskType::class, $userHasProfile, []);
//        $form->handleRequest($this->request);
//        if ($form->isSubmitted() && $form->isValid()) {
//
//
//            // Set manual date.
//            if(!empty($form->get('date_raw')->getData())) {
//                $date = new \DateTime($form->get('date_raw')->getData());
//                $task->setDate($date);
//
//                // Set manual timestart date.
//                if(!empty($form->get('timeStart')->getData())) {
//                    $date = new \DateTime($form->get('date_raw')->getData(). ' ' .$form->get('timeStart')->getData());
//                    $task->setTimeStart($date);
//                }
//            }
//
//            $this->em->persist($task);
//            $this->em->flush();
//
//            return new JsonResponse([
//                'status' => 'success',
//                'response' => ($action == 'create') ? 'Taak is aangemaakt!' : 'Taak is aangepast!',
//                'redirect' => $this->router->generate('agenda_index')
//            ]);
//
//        } elseif ($form->isSubmitted() && !$form->isValid()) {
//
//            return new JsonResponse([
//                'status' => 'error',
//                'response' => 'Er is iets fout gegaan'
//            ]);
//
//        }
//
//        return new Response(
//            $this->templating->render('popup/task_form.html.twig', array(
//                'form' => $form->createView(),
//            ))
//        );
//    }

    public function deleteUserHasProfileAction() {

    }
}