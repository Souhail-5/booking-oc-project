<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Stripe;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Reservation;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\TicketPrice;
use QS\BookingBundle\Entity\EventTicket;
use QS\BookingBundle\Form\OrderGuichetType;
use QS\BookingBundle\Form\OrderInformationType;
use QS\BookingBundle\Form\ReservationType;

class BookingController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('QSBookingBundle:Event')->findAll();
        return $this->render('QSBookingBundle:Booking:index.html.twig', [
            'events' => $events,
        ]);
    }

    public function guichetAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);

        $order = new Order;
        $order->setEvent($event);
        $order->setStatus(Order::STATUS_PENDING);
        $form = $this->createForm(OrderGuichetType::class, $order);
        $form->handleRequest($request);

        $isTicketsAvailable = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $periodService = $this->get('qs_booking.periodService');
            $isTicketsAvailable = $periodService->isDateMatchTickets($order->getEventDate(), $form->get('tickets')->getData());

            if ($isTicketsAvailable) {
                $em->persist($order);
                // $em->flush();
                // return $this->redirectToRoute('qs_booking_information', [
                //     'orderId' => $order->getId(),
                // ]);
            }
        }

        // if ($request->isMethod('POST')) {
        //     // VERIFY TICKET (exist and available) - semidone
        //     // VERIFY Date - done
        //     // VERIFY QTY - done
        //     foreach ($request->request->get('ticket') as $ticketId => $ticket) {
        //         $qty = $ticket['qty'];
        //         $order->setQtyResv($order->getQtyResv() + $qty);
        //         $ticket = $em->getRepository('QSBookingBundle:Ticket')->find($ticketId);
        //         $ticketPrice = $em->getRepository('QSBookingBundle:TicketPrice')->getOneByTicket($ticket);
        //         for ($i=0; $i < $qty; $i++) {
        //             $reservation = (new Reservation)->setTicketPrice($ticketPrice);
        //             $order->addReservation($reservation);
        //         }
        //     }
        // }

        return $this->render('QSBookingBundle:Booking:guichet.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'isTicketsAvailable' => $isTicketsAvailable,
        ]);
    }

    public function informationAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('QSBookingBundle:Order')->find($orderId);
        $form = $this->createForm(OrderInformationType::class, $order);
        $form = $this->createForm(OrderInformationType::class, $order);
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

        if ($request->isMethod('POST')) {
            // stripe
            $stripe = array(
              "secret_key"      => "sk_test_Ykxr69WDKpbHjWWq4v6Zw8Lm",
              "publishable_key" => "pk_test_fdVRc4edwV2ceJjan6KzQFQT"
            );

            Stripe\Stripe::setApiKey($stripe['secret_key']);

            $token  = $_POST['stripeToken'];

            $customer = Stripe\Customer::create(array(
                'email' => 'customer@example.com',
                'source'  => $token
            ));

            $charge = Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => 1600,
                'currency' => 'eur'
            ));

            return $this->redirectToRoute('qs_booking_confirmation', [
                'orderId' => $order->getId(),
            ]);
        }

        return $this->render('QSBookingBundle:Booking:checkout.html.twig', [
            'event' => $order->getEvent(),
            'reservations' => $order->getReservations(),
        ]);
    }

    public function confirmationAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('QSBookingBundle:Order')->find($orderId);

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('contact@qanops.com')
            ->setTo('smsouhail@gmail.com')
            ->setBody(
                'TEST',
                'text/html'
            )
        ;

        $this->get('mailer')->send($message);

        return $this->render('QSBookingBundle:Booking:confirmation.html.twig', [
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
