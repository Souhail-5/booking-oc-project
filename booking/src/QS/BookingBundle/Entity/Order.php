<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="qs_order")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    const STATUS_CANCELED = 0; // Ordering this ticket IS NOT possible for this period
    const STATUS_PENDING = 1; // Ordering this ticket IS possible for this period
    const STATUS_PAID = 2; // Access IS possible with this ticket for this period

    /**
     * @var guid
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_date", type="date")
     */
    private $eventDate;

    /**
     * @var int
     *
     * @ORM\Column(name="qty_resv", type="integer")
     */
    private $qtyResv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersistSetCreatedAt()
    {
        $this->setCreatedAt(new \Datetime());
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdateSetModifiedAt()
    {
        $this->setModifiedAt(new \Datetime());
    }

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Event", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\Reservation", mappedBy="order", cascade={"persist", "remove"})
     */
    private $reservations;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reservations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set eventDate
     *
     * @param \DateTime $eventDate
     *
     * @return Orders
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set qtyResv
     *
     * @param integer $qtyResv
     *
     * @return Orders
     */
    public function setQtyResv($qtyResv)
    {
        $this->qtyResv = $qtyResv;

        return $this;
    }

    /**
     * Get qtyResv
     *
     * @return int
     */
    public function getQtyResv()
    {
        return $this->qtyResv;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Orders
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     *
     * @return Orders
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Orders
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set event
     *
     * @param Event $event
     *
     * @return Order
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Add reservation
     *
     * @param \QS\BookingBundle\Entity\Reservation $reservation
     *
     * @return Order
     */
    public function addReservation(\QS\BookingBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        $reservation->setOrder($this);

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \QS\BookingBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\QS\BookingBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }
}
