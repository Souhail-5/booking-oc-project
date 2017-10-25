<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketPrice
 *
 * @ORM\Table(name="ticket_price")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\TicketPriceRepository")
 */
class TicketPrice
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
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Ticket")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Price")
     * @ORM\JoinColumn(nullable=false)
     */
    private $price;


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
     * Set ticket
     *
     * @param \QS\BookingBundle\Entity\Ticket $ticket
     *
     * @return TicketPrice
     */
    public function setTicket(\QS\BookingBundle\Entity\Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \QS\BookingBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set price
     *
     * @param \QS\BookingBundle\Entity\Price $price
     *
     * @return TicketPrice
     */
    public function setPrice(\QS\BookingBundle\Entity\Price $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return \QS\BookingBundle\Entity\Price
     */
    public function getPrice()
    {
        return $this->price;
    }
}
