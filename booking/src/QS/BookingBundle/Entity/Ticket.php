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
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Price")
     * @ORM\JoinColumn(nullable=false)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\EventPeriod", mappedBy="ticket")
     */
    private $periods;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->periods = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set price
     *
     * @param Price $price
     *
     * @return Ticket
     */
    public function setPrice(Price $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add period
     *
     * @param EventPeriod $period
     *
     * @return Ticket
     */
    public function addPeriod(EventPeriod $period)
    {
        $this->periods[] = $period;

        $period->setTicket($this);

        return $this;
    }

    /**
     * Remove period
     *
     * @param EventPeriod $period
     */
    public function removePeriod(EventPeriod $period)
    {
        $this->periods->removeElement($period);
    }

    /**
     * Get periods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriods()
    {
        return $this->periods;
    }
}
