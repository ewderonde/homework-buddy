<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 10-4-2017
 * Time: 21:00
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class Schedule
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var Task[]
     */
    private $tasks;

    public function __construct()
    {
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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
    public function addTask($task)
    {
        $this->tasks[] = $task;
    }

}