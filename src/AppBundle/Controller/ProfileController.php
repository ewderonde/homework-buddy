<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 14-4-2017
 * Time: 15:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Entity\UserHasProfile;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class ProfileController extends BaseController
{
    public function indexAction() {
        $profileRepository = $this->em->getRepository('AppBundle:Profile');

        return new Response($this->templating->render('profile/index.html.twig', ['title' => "Beheer", 'current_path' => 'manage', 'users' => []]));
    }

    public function activeProfileAction (UserHasProfile $profile) {

    }


    /**
     * Send profile invite for given email address.
     * @return JsonResponse
     */
    public function profileInviteAction()
    {
        $email = $this->request->get('emailaddress', null);
        $userRepo = $this->em->getRepository('AppBundle:User');

        if($email !== null) {
            /** @var User $user */
            $user = $userRepo->findUserByEmail($email);

            if($user != null) {
                $exists = $userRepo->checkForDuplicateInvite($user, $this->profile);

                if($exists == 0) {
                    $userHasProfile = new UserHasProfile();
                    $userHasProfile->setUser($user);
                    $userHasProfile->setProfile($this->profile);

                    $this->em->persist($userHasProfile);
                    $this->em->flush();

                    $this->sendProfileInviteEmailToExistingUser($user);
                } else {
                    $data = array(
                        'status' => 'success',
                        'response' => $user->getFullName() . ' heeft al toegang tot dit profiel'
                    );

                    return new JsonResponse($data);
                }
            }
            // $this->inviteUser($email, $user);
        }

        $data = array(
            'status' => 'success',
            'response' => $email . ' is uitgenodigd voor dit profiel'
        );

        return new JsonResponse($data);
    }

    public function inviteUser($email, $existingUser) {
        /**
         * $userRepo UserRepository
         * $user User
         */
        $userRepo = $this->em->getRepository('AppBundle:User');
        $user = $userRepo->findOneBy(array(
            'email' => trim($email)
        ));

        if($user) {
            $hash = $this->generateInviteHash($user);
            $user->setResetPassword($hash);
            $this->em->flush();

            // send mail
            // return $this->mailManager->sendResetPasswordMail($user, $hash);

        }
        return false;
    }

    public function generateInviteHash($data)
    {
        $now = new \DateTime();

        if ($data instanceof User) {
            $user = $data;
            $inviteData = array(
                $user->getId(),
                $user->getEmail(),
                $user->getFullname(),
                $now->getTimestamp(),
                uniqid()
            );

        }

        return sha1(implode('.', $inviteData));
    }

    public function userInviteAction($hash)
    {
        $userRepo = $this->em->getRepository('AppBundle:User');
        $user = $userRepo->findByResetPasswordHash($hash);
        if(!$user) {
            return new RedirectResponse($this->router->generate('login'));
        }

        $form = $this->formFactory->create(UserResetPasswordType::class, $user, array(
            'showSubmitButton' => true,
            'validation_groups' => array('reset')
        ));

        if($this->request->isMethod('POST')) {

            $data = $this->request->get($form->getName());
            $form->submit($data);
            if ($form->isSubmitted() && $form->isValid()) {

                // reset field
                $user->setResetPassword('');

                // save to db
                $this->em->flush();

                // add message
                $this->session->getFlashBag()->add('success', 'Je wachtwoord is succesvol gewijzigd, je kunt hieronder inloggen.');

                // redirect to loginpage
                return new RedirectResponse($this->router->generate('login'));

            }

        }

        // show form
        return new Response($this->templating->render('security/reset-password.html.twig', array(
            'form' => $form->createView()
        )));
    }

    public function sendProfileInviteEmailToExistingUser(User $user, $hash = null) {
        $url = $this->router->generate('reset_password', array(
            'hash' => $hash
        ), Router::ABSOLUTE_URL);

        $message = \Swift_Message::newInstance()
            ->setSubject( 'Uitnodiging Profiel')
            ->setFrom('no-reply@homework-buddy.nl')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'email/reset_password.html.twig',
                    array(
                        'url' => $url,
                        'name' => $user->getFirstName()
                    )
                ),
                'text/html'
            );




        try {
            $mailer = new \Swift_Mailer();
            $mailer->send($message);
            // $this->mailer->send($message);
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }

}