<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketPeriod
 *
 * @ORM\Table(name="ticket_period")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\TicketPeriodRepository")
 */
class TicketPeriod
{
    const ACTION_EXCLUDE = 0; // Ordering this ticket IS NOT possible for this period
    const ACTION_INCLUDE = 1; // Ordering this ticket IS possible for this period
    const ACTION_ENTER = 3; // Access IS possible with this ticket for this period

    /**
     * @var guid
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="action", type="integer")
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Ticket", inversedBy="ticketPeriods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Period", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $period;


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
     * Set action
     *
     * @param integer $action
     *
     * @return TicketPeriod
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return integer
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set ticket
     *
     * @param Ticket $ticket
     *
     * @return TicketPeriod
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set period
     *
     * @param Period $period
     *
     * @return TicketPeriod
     */
    public function setPeriod(Period $period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return Period
     */
    public function getPeriod()
    {
        return $this->period;
    }
}
