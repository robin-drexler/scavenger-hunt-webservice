<?php

namespace Scavenger\WebserviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scavenger\WebserviceBundle\Entity\Battlezone
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Scavenger\WebserviceBundle\Entity\BattlezoneRepository")
 */
class Battlezone
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @var integer $lng
     *
     * @ORM\Column(name="lng", type="integer")
     */
    private $lng;

    /**
     * @var integer $lat
     *
     * @ORM\Column(name="lat", type="integer")
     */
    private $lat;

    /**
     * @var integer $radius
     *
     * @ORM\Column(name="radius", type="integer")
     */
    private $radius;


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

    /**
     * Set lng
     *
     * @param integer $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return integer 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param integer $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat
     *
     * @return integer 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set radius
     *
     * @param integer $radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    /**
     * Get radius
     *
     * @return integer 
     */
    public function getRadius()
    {
        return $this->radius;
    }
}