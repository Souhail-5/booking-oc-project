<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;
use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Price;

class BookingService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setOrderPrice(Order $order)
    {
        $orderPrice = 0;
        $order->getReservations();
        foreach ($order->getReservations() as $reservation) {
            $visitor = $reservation->getVisitor();
            $ticket = $reservation->getTicketPrice()->getTicket();
            $age = ((new \DateTime(null, new \DateTimeZone($order->getEvent()->getTimeZone())))->diff($visitor->getBirthDate()))->y;
            $orderPrice += $this->getPriceFromAge($age);
            $price = $this->em->getRepository('QSBookingBundle:Price')->findOneByEur($this->getPriceFromAge($age));
            $ticketPrice = $this->em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicketPrice($ticket, $price);
            $reservation->setTicketPrice($ticketPrice);
        }
        $this->em->persist($order);
        $this->em->flush();
        return $orderPrice;
    }

    public function getPriceFromAge($age)
    {
        if ($age < 4) return 0;
        if ($age <= 12) return 8;
        if ($age < 60) return 16;
        return 12;
    }
}
