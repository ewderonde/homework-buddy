<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 10-4-2017
 * Time: 21:10
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class Subject
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
     * @var string
     */
    private $description;

    /**
     * @var Task[]
     */
    private $tasks;

    /**
     * @var Grade[]
     */
    private $grades;

    /**
     * @var Profile
     */
    private $profile;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    /**
     * @return Grade[]
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param Grade[] $grades
     */
    public function setGrades($grades)
    {
        $this->grades = $grades;
    }

    /**
     * @param Grade $grade
     */
    public function addGrade(Grade $grade) {
        $this->grades[] = $grade;
    }

    /**
     * @param Grade $grade
     */
    public function removeGrade($grade)
    {
        $this->grades->removeElement($grade);
        $grade->setSubject(null);
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

    public function toArray() {
        $grades = [];
        foreach($this->grades as $grade) {
            $grades[] = $grade->getGrade();
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'grades' => $grades
        ];
    }
}