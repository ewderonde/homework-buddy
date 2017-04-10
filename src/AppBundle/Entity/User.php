<?php

/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 16-2-2017
 * Time: 22:15
 */
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var UserHasProfile[]
     */
    private $userHasProfiles;

    /**
     * @var Task[]
     */
    private $createdTasks;

    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->firstName . ' '. $this->lastName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->firstName . ' '. $this->lastName;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getFullName() {

        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return UserHasProfile[]
     */
    public function getUserHasProfiles()
    {
        return $this->userHasProfiles;
    }

    /**
     * @param UserHasProfile[] $userHasProfiles
     */
    public function setUserHasProfile($userHasProfiles)
    {
        $this->userHasProfiles = $userHasProfiles;
    }

    /**
     * @param UserHasProfile $userHasProfiles
     */
    public function addUserHasProfile($userHasProfile)
    {
        $this->userHasProfiles[] = $userHasProfile;
    }

    /**
     * @return Task[]
     */
    public function getCreatedTasks()
    {
        return $this->createdTasks;
    }

    /**
     * @param Task[] $createdTasks
     */
    public function setCreatedTasks($createdTasks)
    {
        $this->createdTasks = $createdTasks;
    }

    /**
     * @param Task $createdTasks
     */
    public function addCreatedTask($createdTask)
    {
        $this->createdTasks[] = $createdTask;
    }
}