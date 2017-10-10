<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\Period;

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
    public function getUnavailabilityForEvent(Event $event)
    {
        $periods = [];

        $qb = $this->em->createQueryBuilder()
            ->addSelect('o.eventDate fullDate')
            ->from('QSBookingBundle:Order', 'o')
            ->andWhere('o.event = :event AND o.eventDate >= :today')
            ->addGroupBy('o.eventDate')
            ->andHaving('SUM(o.qtyResv) >= :maxResvDay')
                ->setParameters([
                    'event' => $event,
                    'today' => new \Datetime(null, new \DateTimeZone($event->getTimeZone())),
                    'maxResvDay' => $event->getMaxResvDay(),
                ])
        ;

        $periods = array_merge($periods, $qb->getQuery()->getScalarResult());

        $qb = $this->em->createQueryBuilder()
            ->addSelect('p')
            ->from('QSBookingBundle:Period', 'p')
            ->leftJoin('p.events', 'ep', 'WITH', 'ep.event = :event')
            ->andWhere('ep.action = :action')
                ->setParameters([
                    'event' => $event,
                    'action' => EventPeriod::ACTION_EXCLUDE,
                ])
        ;

        $periods = array_merge($periods, $qb->getQuery()->getScalarResult());

        return $periods;
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
            case 'day':
                return $date->format('w') == $period->getStart();
                break;

            case 'range-todaytime':
                $start = new \DateTime($period->getStart(), new \DateTimeZone('Europe/Paris'));
                $end = new \DateTime($period->getEnd(), new \DateTimeZone('Europe/Paris'));
                return ($date >= $start && $date <= $end) ;
                break;

            default:
                return null;
                break;
        }
    }
}
