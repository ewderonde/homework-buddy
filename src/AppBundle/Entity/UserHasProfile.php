<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 10-4-2017
 * Time: 20:50
 */

namespace AppBundle\Entity;


class UserHasProfile
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var User
     */
    private $user;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var string
     */
    private $profileInvite;

    public function __construct()
    {
        $this->active = 0;
        $this->setDateCreated(new \DateTime());
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return string
     */
    public function getProfileInvite()
    {
        return $this->profileInvite;
    }

    /**
     * @param string $profileInvite
     */
    public function setProfileInvite($profileInvite)
    {
        $this->profileInvite = $profileInvite;
    }


}