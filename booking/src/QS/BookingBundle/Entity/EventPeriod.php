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
    const ACTION_EXCLUDE = 0;
    const ACTION_INCLUDE = 1;

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
     * @ORM\ManyToOne(targetEntity="QS\BookingBundle\Entity\Event", inversedBy="eventPeriods")
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
     * @param integer $action
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
     * @return integer
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
