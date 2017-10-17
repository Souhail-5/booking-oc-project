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
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\Order", mappedBy="event")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity="QS\BookingBundle\Entity\Ticket", inversedBy="events")
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\EventPeriod", mappedBy="event")
     */
    private $eventPeriods;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventPeriods = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * @return Event
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
     * @return Event
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
     * @return Event
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

    /**
     * Add order
     *
     * @param Order $order
     *
     * @return Event
     */
    public function addOrder(Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param Order $order
     */
    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return Event
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Add eventPeriod
     *
     * @param EventPeriod $eventPeriod
     *
     * @return Event
     */
    public function addEventPeriod(EventPeriod $eventPeriod)
    {
        $this->eventPeriods[] = $eventPeriod;

        $eventPeriod->setEvent($this);

        return $this;
    }

    /**
     * Remove eventPeriod
     *
     * @param EventPeriod $eventPeriod
     */
    public function removeEventPeriod(EventPeriod $eventPeriod)
    {
        $this->eventPeriods->removeElement($eventPeriod);
    }

    /**
     * Get eventPeriods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventPeriods()
    {
        return $this->eventPeriods;
    }
}
