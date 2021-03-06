<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;
use QS\BookingBundle\Service\PeriodService;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\TicketPeriod;
use QS\BookingBundle\Entity\Reservation;
use QS\BookingBundle\Entity\Visitor;
use Symfony\Component\Form\Form;
use Stripe;

class BookingService
{
    private $em;
    private $periodService;
    private $orderLimitTime;
    private $stripeSKey;

    public function __construct(EntityManager $em, PeriodService $periodService, $orderLimitTime, $stripeSKey)
    {
        $this->em = $em;
        $this->periodService = $periodService;
        $this->orderLimitTime = $orderLimitTime;
        $this->stripeSKey = $stripeSKey;
    }

    public function calcOrderPrice(Order $order)
    {
        $order->setTotalPrice(0);
        foreach ($order->getReservations() as $reservation) {
            $visitor = $reservation->getVisitor();
            $ticket = $reservation->getTicketPrice()->getTicket();
            $price = $this->getPriceFromOrderVisitor($order, $visitor);
            $order->setTotalPrice($order->getTotalPrice() + $price);
            $price = $this->em->getRepository('QSBookingBundle:Price')->findOneByEur($price);
            $ticketPrice = $this->em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicketPrice($ticket, $price);
            $reservation->setTicketPrice($ticketPrice);
        }
    }

    public function getPriceFromOrderVisitor(Order $order, Visitor $visitor)
    {
        $age = ((new \DateTime($order->getEventDate()->format('Y-m-d'), new \DateTimeZone($order->getEvent()->getTimeZone())))->diff($visitor->getBirthDate()))->y;
        if ($age < 4):
            $price = 0;
        elseif ($age <= 12):
            $price = 8;
        elseif ($age < 60):
            $price = 16;
        else:
            $price = 12;
        endif;
        return $visitor->getDiscount() ? ($price >= 10 ? 10 : $price) : $price;
    }

    public function isFullEventDate(Event $event, \DateTime $date)
    {
        $totalQtyResv = $this->em->getRepository('QSBookingBundle:Order')->getTotalQtyResvByEventDateStatuses($event, $date, [
            Order::STATUS_PAID,
            Order::STATUS_PENDING,
        ]);
        if ($totalQtyResv >= $event->getMaxResvDay()) return true;
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

    public function isCanceledOrder(Order $order)
    {
        if ($order->getStatus() == Order::STATUS_CANCELED) return true;
        if ($order->getStatus() != Order::STATUS_PAID && ((new \DateTime('-'.$this->orderLimitTime.' minutes', (new \DateTimeZone($order->getEvent()->getTimeZone())))) > $order->getCreatedAt())) {
            $order->setStatus(Order::STATUS_CANCELED);
            $this->em->persist($order);
            $this->em->flush();
            return true;
        }
        return false;
    }

    public function bookOrder(Form $form)
    {
        $order = $form->getData();
        foreach ($form->get('tickets') as $ticket) {
            $qty = $ticket->get('qty')->getData();
            $ticket = $this->em->getRepository('QSBookingBundle:Ticket')->find($ticket->getData()->getId());
            $ticketPrice = $this->em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicket($ticket);
            for ($i=0; $i < $qty; $i++) {
                $order->addReservation((new Reservation)->setTicketPrice($ticketPrice));
            }
        }
        $this->em->persist($order);
        $this->em->flush();
    }

    public function stripeCheckout(Order $order, $token)
    {
        try {
            if ($order->getTotalPrice() == 0) return true;
            Stripe\Stripe::setApiKey($this->stripeSKey);
            $customer = Stripe\Customer::create([
                'email' => $order->getEmail(),
                'source'  => $token,
            ]);
            Stripe\Charge::create([
                'customer' => $customer->id,
                'amount'   => $order->getTotalPrice() * 100,
                'currency' => 'eur',
            ]);
            return true;
        } catch(Stripe\Error\Card $e) {
            return false;
        }
    }

    public function cancelExpiredOrders()
    {
        $this->em->getRepository('QSBookingBundle:Order')->updateCancelExpiredOrders($this->orderLimitTime);
    }
}
