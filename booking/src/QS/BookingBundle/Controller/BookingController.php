<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Reservation;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\TicketPrice;
use QS\BookingBundle\Entity\EventTicket;
use QS\BookingBundle\Form\OrderType;
use QS\BookingBundle\Form\ReservationType;

class BookingController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('QSBookingBundle:Event')->findAll();
        dump($events);
        return $this->render('QSBookingBundle:Booking:index.html.twig', [
            'events' => $events,
        ]);
    }

    public function guichetAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);

        if ($request->isMethod('POST')) {
            // VERIFY DATE
            $periodService = $this->get('qs_booking.period');
            $now = new \Datetime(null, new \DateTimeZone($event->getTimeZone()));
            $date = $now->modify($request->request->get('eventDate'));
            // $bool = $periodService->isDateMatchEvent($date, $event);
            // VERIFY TICKET
            // VERIFY QTY
            $order = new Order;
            $order->setEvent($event);
            $order->setEventDate($date);
            foreach ($request->request->get('ticket') as $ticketId => $ticket) {
                $qty = $ticket['qty'];
                $order->setQtyResv($order->getQtyResv() + $qty);
                $ticket = $em->getRepository('QSBookingBundle:Ticket')->find($ticketId);
                $ticketPrice = $em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicket($ticket);
                for ($i=0; $i < $qty; $i++) {
                    $reservation = (new Reservation)->setTicketPrice($ticketPrice);
                    $order->addReservation($reservation);
                }
            }
            $order->setStatus(Order::STATUS_PENDING);
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('qs_booking_information', [
                'orderId' => $order->getId(),
            ]);
        }

        return $this->render('QSBookingBundle:Booking:guichet.html.twig', [
            'event' => $event,
        ]);
    }

    public function informationAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('QSBookingBundle:Order')->find($orderId);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('qs_booking_checkout', [
                'orderId' => $order->getId(),
            ]);
        }

        return $this->render('QSBookingBundle:Booking:information.html.twig', [
            'event' => $order->getEvent(),
            'form' => $form->createView(),
        ]);
    }

    public function checkoutAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('QSBookingBundle:Order')->find($orderId);

        return $this->render('QSBookingBundle:Booking:checkout.html.twig', [
            'event' => $order->getEvent(),
            'reservations' => $order->getReservations(),
        ]);
    }

    public function cgvAction()
    {
        return $this->render('QSBookingBundle:Booking:cgv.html.twig');
    }

    public function cguAction()
    {
        return $this->render('QSBookingBundle:Booking:cgu.html.twig');
    }

    public function legalAction()
    {
        return $this->render('QSBookingBundle:Booking:mentions-legales.html.twig');
    }
}
