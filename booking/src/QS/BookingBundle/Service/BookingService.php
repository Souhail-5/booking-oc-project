<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;
use QS\BookingBundle\Service\PeriodService;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\TicketPeriod;
use QS\BookingBundle\Entity\Reservation;
use Symfony\Component\Form\Form;

class BookingService
{
    private $em;
    private $periodService;

    public function __construct(EntityManager $em, PeriodService $periodService)
    {
        $this->em = $em;
        $this->periodService = $periodService;
    }

    public function calcOrderPrice(Order $order)
    {
        foreach ($order->getReservations() as $reservation) {
            $visitor = $reservation->getVisitor();
            $ticket = $reservation->getTicketPrice()->getTicket();
            $age = ((new \DateTime(null, new \DateTimeZone($order->getEvent()->getTimeZone())))->diff($visitor->getBirthDate()))->y;
            $order->setTotalPrice($order->getTotalPrice() + $this->getPriceFromAge($age));
            $price = $this->em->getRepository('QSBookingBundle:Price')->findOneByEur($this->getPriceFromAge($age));
            $ticketPrice = $this->em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicketPrice($ticket, $price);
            $reservation->setTicketPrice($ticketPrice);
        }
    }

    public function getPriceFromAge($age)
    {
        if ($age < 4) return 0;
        if ($age <= 12) return 8;
        if ($age < 60) return 16;
        return 12;
    }

    public function isFullEvent(Event $event, \DateTime $date)
    {
        if ($this->em->getRepository('QSBookingBundle:Event')->getTotalQtyResvByEventDate($event, $date)
            >= $event->getMaxResvDay()) return true;
        return false;
    }

    public function getAvailableTicketsByEventDate(Event $event, \DateTime $date)
    {
        $tickets = [];

        foreach ($this->em->getRepository('QSBookingBundle:Ticket')->getAllByEvent($event) as $t) {
            foreach ($t->getTicketPeriods() as $tp) {
                if (
                    ($tp->getAction() == TicketPeriod::ACTION_INCLUDE && !$this->periodService->isDateMatchPeriod($date, $tp->getPeriod()))
                    || ($tp->getAction() == TicketPeriod::ACTION_EXCLUDE && $this->periodService->isDateMatchPeriod($date, $tp->getPeriod()))
                ) {
                    continue 2;
                }
            }
            $tickets[] = $t;
        }

        return $tickets;
    }

    public function bookOrder(Form $form)
    {
        $order = $form->getData();

        foreach ($form->get('tickets') as $ticket) {
            $qty = $ticket->get('qty')->getData();
            $order->setQtyResv($order->getQtyResv() + $qty);
            $ticket = $this->em->getRepository('QSBookingBundle:Ticket')->find($ticket->getData()->getId());
            $ticketPrice = $this->em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicket($ticket);
            for ($i=0; $i < $qty; $i++) {
                $order->addReservation((new Reservation)->setTicketPrice($ticketPrice));
            }
        }

        $this->em->persist($order);
        $this->em->flush();
    }
}
