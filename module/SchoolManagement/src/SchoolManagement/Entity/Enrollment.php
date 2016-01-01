<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SchoolManagement\Entity;

use Doctrine\ORM\Mapping as ORM;
use SchoolManagement\Entity\StudentClass;
use Recruitment\Entity\Registration;

/**
 * Description of Entrollment
 *
 * @author Márcio Dias <marciojr91@gmail.com>
 * @ORM\Table(name="enrollment", 
 *      uniqueConstraints={@ORM\UniqueConstraint(name="class_registration_idx", 
 *          columns={"class_id", "registration_id"})}
 * )
 * @ORM\Entity
 */
class Enrollment
{

    /**
     *
     * @var integer
     * @ORM\Column(name="enrollment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $enrollmentId;

    /**
     * @var SchoolManagement\Entity\StudentClass
     * @ORM\ManyToOne(targetEntity="SchoolManagement\Entity\StudentClass", inversedBy="enrollments", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="class_id", nullable=false)
     */
    private $class;

    /**
     * @var Recruitment\Entity\Registration
     * @ORM\ManyToOne(targetEntity="Recruitment\Entity\Registration", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="registration_id", referencedColumnName="registration_id", nullable=false)
     */
    private $registration;

    /**
     * @var \DateTime
     * @ORM\Column(name="enrollment_begindate", type="datetime", nullable=false)
     */
    private $enrollmentBeginDate;

    /**
     * @var mixed
     * @ORM\Column(name="enrollment_enddate", type="datetime", nullable=true)
     */
    private $enrollmentEndDate;

    public function __construct()
    {
        $this->enrollmentBeginDate = new \DateTime('now');
    }

    /**
     * 
     * @return integer
     */
    public function getEnrollmentId()
    {
        return $this->enrollmentId;
    }

    /**
     * @return SchoolManagement\Entity\StudentClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Recruitment\Entity\Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getEnrollmentBeginDate()
    {
        return $this->enrollmentBeginDate;
    }

    /**
     * 
     * @return mixed \DateTime | null
     */
    public function getEnrollmentEndDate()
    {
        return $this->enrollmentEndDate;
    }

    /**
     * 
     * @param StudentClass $class
     * @return SchoolManagement\Entity\Enrollment
     */
    public function setClass(StudentClass $class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * 
     * @param Recruitment\Entity\Registration $registration
     * @return SchoolManagement\Entity\Enrollment
     */
    public function setRegistration(Registration $registration)
    {
        $this->registration = $registration;
        return $this;
    }

    /**
     * 
     * @param \DateTime $enrollmentBeginDate
     * @return SchoolManagement\Entity\Enrollment
     */
    public function setEnrollmentBeginDate(\DateTime $enrollmentBeginDate)
    {
        $this->enrollmentBeginDate = $enrollmentBeginDate;
        return $this;
    }

    /**
     * 
     * @param mixed \DateTime | null $enrollmentEndDate
     * @return SchoolManagement\Entity\Enrollment
     */
    public function setEnrollmentEndDate($enrollmentEndDate)
    {
        $this->enrollmentEndDate = $enrollmentEndDate;
        return $this;
    }

}