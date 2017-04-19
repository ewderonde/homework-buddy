<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 14-4-2017
 * Time: 15:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Entity\UserHasProfile;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class ProfileController extends BaseController
{
    public function indexAction() {
        $profileRepository = $this->em->getRepository('AppBundle:Profile');
        $users = $profileRepository->getAllUsersWithPermission($this->profile, $this->user);
        $currentProfile = $profileRepository->getCurrentUserHasProfile($this->user, $this->profile);
        $profiles = $profileRepository->getAllUserProfiles($this->user);

        return new Response($this->templating->render('profile/index.html.twig', ['title' => "Beheer", 'current_path' => 'manage', 'users' => $users, 'profiles' => $profiles, 'current_profile' => $currentProfile ]));
    }

    public function activeProfileAction (UserHasProfile $profile) {
        $profileRepo = $this->em->getRepository('AppBundle:Profile');
        $profiles = $profileRepo->getAllActiveProfiles($this->user, $profile->getId());


        /** @var UserHasProfile $uhp */
        foreach($profiles as $uhp) {
            $uhp->setActive(0);
        }

        $profile->setActive(1);
        $this->em->flush();
//        Debug::dump($profiles);
//        exit;
        return new RedirectResponse($this->router->generate('profile_index'));
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

                    $data = array(
                        'status' => 'success',
                        'response' => '<strong>'.$user->getFullName() .'</strong> is uitgenodigd voor dit profiel'
                    );

                    return new JsonResponse($data);
                } else {
                    $data = array(
                        'status' => 'warning',
                        'response' => '<strong>'.$user->getFullName() .'</strong> heeft al toegang tot dit profiel'
                    );

                    return new JsonResponse($data);
                }
            } else {
                // Create new user invite.
                $userHasProfile = new UserHasProfile();
                $userHasProfile->setUser(null);
                $userHasProfile->setProfile($this->profile);
                $userHasProfile->setProfileInvite($this->generateInviteHash($userHasProfile));

                $this->em->persist($userHasProfile);
                $this->em->flush();

                $this->sendProfileInviteEmailToNewUser($userHasProfile, $email);

                $data = array(
                    'status' => 'success',
                    'response' => '<strong>'.$email .'</strong> is uitgenodigd voor dit profiel'
                );

                return new JsonResponse($data);
            }
        }

        $data = array(
            'status' => 'warning',
            'response' => 'Je hebt geen geldig emailadres ingevoerd.'
        );

        return new JsonResponse($data);
    }

    public function generateInviteHash($data)
    {
        $now = new \DateTime();
        /** @var UserHasProfile $uhp */
        $uhp = $data;
        $inviteData = array(
            $uhp->getProfile()->getTitle(),
            $now->getTimestamp(),
            uniqid()
        );

        return sha1(implode('.', $inviteData));
    }

    public function sendProfileInviteEmailToExistingUser(User $user) {
        $url = $this->router->generate('login', [], Router::ABSOLUTE_URL);

        $message = \Swift_Message::newInstance()
            ->setSubject( 'Uitnodiging Profiel')
            ->setFrom('info@homework-buddy.nl')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'email/template.html.twig',
                    array(
                        'url' => $url,
                        'call_to_action_text' => 'Inloggen',
                        'name' => $user->getFirstName(),
                        'sender' => $this->user->getFullName(),
                        'new_user' => false
                    )
                ),
                'text/html'
            );

        try {
            $this->mailer->send($message);
        } catch (Exception $ex) {
            Debug::dump($ex);
            return false;
        }

        return true;
    }

    public function sendProfileInviteEmailToNewUser(UserHasProfile $uhp, $email) {
        $url = $this->router->generate('register_through_invite', ['hash' => $uhp->getProfileInvite() ], Router::ABSOLUTE_URL);

        $message = \Swift_Message::newInstance()
            ->setSubject( 'Uitnodiging Profiel')
            ->setFrom('info@homework-buddy.nl')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'email/template.html.twig',
                    array(
                        'url' => $url,
                        'call_to_action_text' => 'Registreren',
                        'name' => '',
                        'sender' => $this->user->getFullName(),
                        'new_user' => true
                    )
                ),
                'text/html'
            );

        try {
            $this->mailer->send($message);
        } catch (Exception $ex) {
            Debug::dump($ex);
            return false;
        }

        return true;
    }
}