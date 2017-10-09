<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\EventPeriod;

class Period
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
            ->andWhere('o.event = :event')
            ->addGroupBy('o.eventDate')
            ->andHaving('SUM(o.qtyResv) >= :maxResvDay')
                ->setParameters([
                    'event' => $event,
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
}
