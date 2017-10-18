<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\TicketPeriod", mappedBy="ticket")
     */
    private $ticketPeriods;

    /**
     * @ORM\ManyToMany(targetEntity="QS\BookingBundle\Entity\Event", mappedBy="tickets")
     */
    private $events;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ticketPeriods = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param guid $id
     *
     * @return Tickets
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tickets
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
     * Set description
     *
     * @param string $description
     *
     * @return Tickets
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add ticketPeriod
     *
     * @param TicketPeriod $ticketPeriod
     *
     * @return Ticket
     */
    public function addTicketPeriod(TicketPeriod $ticketPeriod)
    {
        $this->ticketPeriods[] = $ticketPeriod;

        $ticketPeriod->setTicket($this);

        return $this;
    }

    /**
     * Remove ticketPeriod
     *
     * @param TicketPeriod $ticketPeriod
     */
    public function removeTicketPeriod(TicketPeriod $ticketPeriod)
    {
        $this->ticketPeriods->removeElement($ticketPeriod);
    }

    /**
     * Get ticketPeriods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTicketPeriods()
    {
        return $this->ticketPeriods;
    }

    /**
     * Add event
     *
     * @param \QS\BookingBundle\Entity\Event $event
     *
     * @return Ticket
     */
    public function addEvent(\QS\BookingBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \QS\BookingBundle\Entity\Event $event
     */
    public function removeEvent(\QS\BookingBundle\Entity\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }
}
