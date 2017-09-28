<?php

namespace QS\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventPeriod
 *
 * @ORM\Table(name="event_period")
 * @ORM\Entity(repositoryClass="QS\BookingBundle\Repository\EventPeriodRepository")
 */
class EventPeriod
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
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Event", inversedBy="periods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Period")
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
     * @param string $action
     *
     * @return EventPeriod
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set event
     *
     * @param Event $event
     *
     * @return EventPeriod
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
     * Set period
     *
     * @param Period $period
     *
     * @return EventPeriod
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
