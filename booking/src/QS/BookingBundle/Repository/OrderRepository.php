<?php

namespace QS\BookingBundle\Repository;

use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Event;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTotalQtyResvByEventDateStatuses(Event $event, \DateTime $date, array $statuses)
    {
        $qb = $this->createQueryBuilder('o')
            ->innerJoin('o.event', 'e', 'WITH', 'o.event = :event AND o.eventDate = :date AND o.status IN (:statuses)')
            ->select('SUM(o.qtyResv) totalQtyResv')
            ->addGroupBy('o.eventDate')
                ->setParameters([
                    'event' => $event,
                    'date' => new \Datetime($date->format('Y-m-d'), new \DateTimeZone($event->getTimeZone())),
                    'statuses' => $statuses,
                ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
        return $qb ? $qb['totalQtyResv'] : 0;
    }

    public function updateCancelExpiredOrders($orderLimitTime)
    {
        return $this->_em->createQueryBuilder()
            ->update('QSBookingBundle:Order', 'o')
            ->set('o.status', ':newStatus')
            ->set('o.modifiedAt', ':modifiedAt')
            ->andWhere('o.status = :status')
            ->andWhere(':expiryDate > o.createdAt')
                ->setParameters([
                    'newStatus' => Order::STATUS_CANCELED,
                    'modifiedAt' => new \DateTime(),
                    'status' => Order::STATUS_PENDING,
                    'expiryDate' => new \DateTime('-'.$orderLimitTime.' minutes'),
                ])
            ->getQuery()
            ->execute()
        ;
    }
}
