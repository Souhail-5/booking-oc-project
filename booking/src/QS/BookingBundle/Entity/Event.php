<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var guid
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=191, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="max_resv_day", type="integer")
     */
    private $maxResvDay;

    /**
     * @var string
     *
     * @ORM\Column(name="time_zone", type="string", length=255)
     */
    private $timeZone;


    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Events
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Events
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set maxResvDay
     *
     * @param integer $maxResvDay
     *
     * @return Events
     */
    public function setMaxResvDay($maxResvDay)
    {
        $this->maxResvDay = $maxResvDay;

        return $this;
    }

    /**
     * Get maxResvDay
     *
     * @return integer
     */
    public function getMaxResvDay()
    {
        return $this->maxResvDay;
    }

    /**
     * Set timeZone
     *
     * @param string $timeZone
     *
     * @return Event
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }
}
