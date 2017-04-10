<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 18-2-2017
 * Time: 17:51
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class UserController extends BaseController
{
    public function registerAction() {
        // Create new User object.
        $user = new User();
        // Create new User Form.
        $form = $this->formFactory->create(UserType::class, $user);
        // Handle Request.
        $form->handleRequest($this->request);

        // Check if the information is valid, when form is submitted.
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);

            // Set encoded password.
            $user->setPassword($encoded);

            // Tell the database to watch for this object.
            $this->em->persist($user);

            // Insert object into database. (Create user row)
            $this->em->flush();


            return new RedirectResponse($this->router->generate('register_success', array(
            )));
        }

        return new Response($this->templating->render('security/register.html.twig', [
            'form' => $form->createView()
        ]));
    }

    public function registerSuccessAction() {
        return new Response($this->templating->render('security/register_success.html.twig'));
    }

}