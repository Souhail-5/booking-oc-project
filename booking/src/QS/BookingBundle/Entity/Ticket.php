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
     * @param \QS\BookingBundle\Entity\Price $price
     *
     * @return Ticket
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
