<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use QS\BookingBundle\Validator\Constraints as QSAssert;

/**
 * Order
 *
 * @ORM\Table(name="qs_order")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Assert\GroupSequence({"guichet", "Order"})
 * @QSAssert\BookOrder
 */
class Order
{
    const STATUS_CANCELED = 0;
    const STATUS_PENDING = 1;
    const STATUS_PAID = 2;

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
     * @Assert\NotBlank(message = "Veuillez sélectionner une date.", groups = {"guichet"})
     * @Assert\Date(message = "La date choisie n'est pas au bon format.", groups = {"guichet"})
     */
    private $eventDate;

    /**
     * @var int
     *
     * @ORM\Column(name="qty_resv", type="integer")
     */
    private $qtyResv;

    /**
     * @var int
     *
     * @ORM\Column(name="total_price", type="integer")
     */
    private $totalPrice;

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
     * @Assert\NotBlank(message = "Vous devez obligatoirement indiquer votre e-mail, nous vous enverrons de cette façon vos billets.", groups = {"guichet"})
     * @Assert\Email(message = "L' e-mail fourni {{ value }} n'est pas au bon format.", groups = {"guichet"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Event", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="QS\BookingBundle\Entity\Reservation", mappedBy="order", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $reservations;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->totalPrice = 0;
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
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return Order
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return integer
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
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
