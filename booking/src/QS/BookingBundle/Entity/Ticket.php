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
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\TicketPeriod", mappedBy="ticket")
     */
    private $ticketPeriods;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ticketPeriods = new \Doctrine\Common\Collections\ArrayCollection();
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
}
