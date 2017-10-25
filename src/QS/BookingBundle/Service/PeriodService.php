<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\TicketPeriod;

class PeriodService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get all unavailable Period for an event
     *
     * @param Event $event
     *
     * @return Array
     */
    public function getUnavailablePeriodsByEvent(Event $event)
    {
        return $this->em->getRepository('QSBookingBundle:Period')->getAllByEventActions($event, [
            EventPeriod::ACTION_EXCLUDE,
        ], true);
    }

    /**
     * Check if date match a period
     *
     * @param Date   $date
     * @param Period $period
     *
     * @return boolean
     */
    public function isDateMatchPeriod(\Datetime $date, Period $period)
    {
        switch ($period->getType()) {
            case 'range-date':
                $start = new \DateTime($period->getStart(), new \DateTimeZone('Europe/Paris'));
                if ($period->getEnd() == 'infinite') {
                    return ($date >= $start);
                    break;
                }
                $end = new \DateTime($period->getEnd(), new \DateTimeZone('Europe/Paris'));
                return ($date >= $start && $date <= $end);
                break;

            case 'month-day_nbr':
                return $date->format('m-d') == $period->getStart();
                break;

            case 'day':
                return $date->format('w') == $period->getStart();
                break;

            case 'range-todaytime':
                $start = new \DateTime($period->getStart(), new \DateTimeZone('Europe/Paris'));
                $end = new \DateTime($period->getEnd(), new \DateTimeZone('Europe/Paris'));
                return ($date >= $start && $date <= $end);
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Check if date match an Event
     *
     * @param Date   $date
     * @param Event  $event
     *
     * @return boolean
     */
    public function isDateMatchEvent(\Datetime $date, Event $event)
    {
        $ps = $event->getEventPeriods();
        foreach ($ps as $p) {
            if (
                ($p->getAction() == EventPeriod::ACTION_INCLUDE && !$this->isDateMatchPeriod($date, $p->getPeriod()))
                || ($p->getAction() == EventPeriod::ACTION_EXCLUDE && $this->isDateMatchPeriod($date, $p->getPeriod()))
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if date match an Ticket
     *
     * @param Date   $date
     * @param Ticket  $ticket
     *
     * @return boolean
     */
    public function isDateMatchTicket(\Datetime $date, Ticket $ticket)
    {
        $ps = $ticket->getTicketPeriods();
        foreach ($ps as $p) {
            if (
                ($p->getAction() == TicketPeriod::ACTION_INCLUDE && !$this->isDateMatchPeriod($date, $p->getPeriod()))
                || ($p->getAction() == TicketPeriod::ACTION_EXCLUDE && $this->isDateMatchPeriod($date, $p->getPeriod()))
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if date match multiple Tickets
     *
     * @param Date   $date
     * @param Array  $tickets
     *
     * @return boolean
     */
    public function isDateMatchTickets(\Datetime $date, Array $tickets)
    {
        foreach ($tickets as $ticket) {
            if (!$this->isDateMatchTicket($date, $ticket)) return false;
        }
        return true;
    }
}
