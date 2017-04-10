<?php
/**
 * Created by PhpStorm.
 * User: Sven de Ronde
 * Date: 10-4-2017
 * Time: 21:17
 */

namespace AppBundle\Entity;


class Grade
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $grade;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Subject
     */
    private $subject;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param float $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
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
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param Subject $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


}