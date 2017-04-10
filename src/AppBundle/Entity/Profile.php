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
     * @var Schedule[]
     */
    private $schedules;

    /**
     * @var Subject[]
     */
    private $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->profileHasUsers = new ArrayCollection();
        $this->schedules = new ArrayCollection();
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
     * @return Schedule[]
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * @param Schedule[] $schedules
     */
    public function setSchedules($schedules)
    {
        $this->schedules = $schedules;
    }

    /**
     * @param Schedule $schedule
     */
    public function addSchedule(Schedule $schedule)
    {
        $this->schedules[] = $schedule;
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