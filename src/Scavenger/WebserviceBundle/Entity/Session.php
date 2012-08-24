<?php

namespace Scavenger\WebserviceBundle\Entity;

use Scavenger\WebserviceBundle\Entity\Battlezone;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\ORM\Mapping\OneToOne;
use \Doctrine\ORM\Mapping\ManyToMany;
use \Doctrine\ORM\Mapping\JoinTable;
use \Doctrine\ORM\Mapping\JoinColumn;

/**
 * Scavenger\WebserviceBundle\Entity\Session
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scavenger\WebserviceBundle\Entity\SessionRepository")
 */
class Session
{

    const SESSION_ACTIVE = 0; //lol

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer $mrX
     *
     * @ORM\Column(name="mrX", type="integer")
     */
    private $mrX;


     /**
     * @var integer $causer
     *
     * @ORM\Column(name="causer", type="integer")
     */
    private $causer = 0;


    /**
     * @var integer $statusCode
     *
     * @ORM\Column(name="status_code", type="integer", nullable=false)
     */
    private $statusCode = self::SESSION_ACTIVE;


    /**
     * @ManyToMany(targetEntity="Scavenger\WebserviceBundle\Entity\User", mappedBy="sessions")
     */
    private $users;

    /**
     * @OneToOne(targetEntity="Scavenger\WebserviceBundle\Entity\Battlezone", cascade={"all"})
     * @JoinColumn(name="battlezone_id", referencedColumnName="id")
     */
    private $battlezone;

    /**
     * @var integer
     * @ORM\Column(name="start", type="integer")
     */
    private $start = 0;

    /**
     * @var integer
     * @ORM\Column(name="end", type="integer")
     */
    private $end = 0;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setBattlezone($battlezone) {
        $this->battlezone = $battlezone;
    }

    /**
     * @return Battlezone
     */
    public function getBattlezone() {
        return $this->battlezone;
    }

    /**
     * Set mrX
     *
     * @param integer $mrX
     */
    public function setMrX($mrX)
    {
        $this->mrX = $mrX;
    }

    /**
     * Get mrX
     *
     * @return integer
     */
    public function getMrX()
    {
        return $this->mrX;
    }

    /**
     * @return Scavenger\WebserviceBundle\Entity\User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @param $statusCode
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $causer
     */
    public function setCauser($causer)
    {
        $this->causer = $causer;
    }

    /**
     * @return int
     */
    public function getCauser()
    {
        return $this->causer;
    }

    /**
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }


    /**
     *
     * @param int $dateTimeStamp
     */
    public function setStart($dateTimeStamp)
    {
        $this->start = $dateTimeStamp;
    }

    /**
     *
     * @param int $dateTimeStamp
     */
    public function setEnd($dateTimeStamp)
    {
        $this->end = $dateTimeStamp;
    }

}