<?php

namespace Scavenger\WebserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scavenger\WebserviceBundle\Entity\UserLocation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scavenger\WebserviceBundle\Entity\UserLocationRepository")
 */
class UserLocation
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Scavenger\WebserviceBundle\Entity\User", inversedBy="locations")
     */
    private $user;

    /**
     * @var int $lat
     *
     * @ORM\Column(name="lat", type="integer")
     */
    private $lat;

    /**
     * @var float $lng
     *
     * @ORM\Column(name="lng", type="integer")
     */
    private $lng;


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
     * Set user_id
     *
     * @param integer $userId
     */
    public function setUser($userId)
    {
        $this->user = $userId;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set lat
     *
     * @param int $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat
     *
     * @return int
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }
}