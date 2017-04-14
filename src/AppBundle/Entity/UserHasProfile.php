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

    public function __construct()
    {
        $this->active = 0;
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


}