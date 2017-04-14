<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 14-4-2017
 * Time: 15:33
 */

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Response;

class ProfileController extends BaseController
{
    public function indexAction() {
        $profileRepository = $this->em->getRepository('AppBundle:Profile');

        return new Response($this->templating->render('profile/index.html.twig', ['title' => "Agenda's"]));
    }

    public function activeProfileAction () {

    }

    public function deleteProfileAction() {

    }
}