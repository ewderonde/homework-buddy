<?php

/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 17-4-2017
 * Time: 15:14
 */
namespace AppBundle\EventListener;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Entity\UserHasProfile;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntityListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if ($entity instanceof User) {
            $this->handleUser($args);
        }
        return;
    }



    public function handleUser(LifecycleEventArgs $args) {
        /** @var User $user */
        $user = $args->getEntity();
        $em = $args->getEntityManager();

        $profile = new Profile();
        $profile->setTitle('Profiel van '. $user->getFullName());

        $userHasProfile = new UserHasProfile();
        $userHasProfile->setUser($user);
        $userHasProfile->setProfile($profile);
        $userHasProfile->setActive(1);

        // Tell doctrine to watch for this object.
        $em->persist($userHasProfile);
        $em->persist($profile);
    }
}