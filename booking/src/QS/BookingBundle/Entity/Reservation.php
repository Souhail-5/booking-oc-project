<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Order")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\TicketPrice")
     */
    private $ticketPrice;

    /**
     * @ORM\OneToOne(targetEntity="QS\BookingBundle\Entity\Visitor", cascade={"persist", "remove"})
     */
    private $visitor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order
     *
     * @param Order $order
     *
     * @return Reservation
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set ticketPrice
     *
     * @param TicketPrice $ticketPrice
     *
     * @return Reservation
     */
    public function setTicketPrice(TicketPrice $ticketPrice = null)
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    /**
     * Get ticketPrice
     *
     * @return TicketPrice
     */
    public function getTicketPrice()
    {
        return $this->ticketPrice;
    }

    /**
     * Set visitor
     *
     * @param Visitor $visitor
     *
     * @return Reservation
     */
    public function setVisitor(Visitor $visitor = null)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * Get visitor
     *
     * @return Visitor
     */
    public function getVisitor()
    {
        return $this->visitor;
    }
}
