<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 10-4-2017
 * Time: 20:51
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class Profile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var UserHasProfile[]
     */
    private $profileHasUsers;

    /**
     * @var Task[]
     */
    private $tasks;

    /**
     * @var Subject[]
     */
    private $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->profileHasUsers = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param Task[] $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @param Task $task
     */
    public function addTask(Schedule $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * @return UserHasProfile[]
     */
    public function getProfileHasUsers()
    {
        return $this->profileHasUsers;
    }

    /**
     * @param UserHasProfile[] $profileHasUsers
     */
    public function setProfileHasUsers($profileHasUsers)
    {
        $this->profileHasUsers = $profileHasUsers;
    }

    /**
     * @param UserHasProfile $userHasProfile
     */
    public function addUserhasProfile($userHasProfile)
    {
        $this->profileHasUsers[] = $userHasProfile;
    }

    /**
     * @return Subject[]
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @param Subject[] $subjects
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * @param Subject $subject
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects[] = $subject;
    }
}