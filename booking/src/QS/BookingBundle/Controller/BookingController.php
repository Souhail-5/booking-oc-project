<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
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
        return $this->render('QSBookingBundle:Booking:index.html.twig', ['events' => $events]);
    }

    public function guichetAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $bookingService = $this->get('qs_booking.bookingService');
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);
        if (!$event) throw $this->createNotFoundException("L'évènement n'existe pas");
        $order = new Order;
        $order->setEvent($event);
        $order->setStatus(Order::STATUS_PENDING);
        $form = $this->createForm(OrderGuichetType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookingService->bookOrder($form);
            return $this->redirectToRoute('qs_booking_information', ['orderId' => $order->getId()]);
        }
        return $this->render('QSBookingBundle:Booking:guichet.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    public function informationAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('QSBookingBundle:Order')->find($orderId);
        if (!$order) throw $this->createNotFoundException("La commande n'existe pas");
        $bookingService = $this->get('qs_booking.bookingService');
        if ($bookingService->isCanceledOrder($order)) return $this->render('QSBookingBundle:Booking:order-canceled.html.twig');
        $form = $this->createForm(OrderInformationType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('qs_booking_checkout', ['orderId' => $order->getId()]);
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
        if (!$order) throw $this->createNotFoundException("La commande n'existe pas");
        $bookingService = $this->get('qs_booking.bookingService');
        if ($bookingService->isCanceledOrder($order)) return $this->render('QSBookingBundle:Booking:order-canceled.html.twig');
        $validator = $this->get('validator');
        if (count($validator->validate($order)) > 0) return $this->redirectToRoute('qs_booking_information', ['orderId' => $order->getId()]);
        $bookingService->calcOrderPrice($order);
        if ($request->isMethod('POST')) {
            if (!$bookingService->stripeCheckout($order, $request->request->get('stripeToken'))) {
                $request->getSession()->getFlashBag()->add('stripe', "Une erreur est surevenue et le paiement n'a pas pû être enregistré. Merci de réessayer.");
            } else {
                $order->setStatus(Order::STATUS_PAID);
                $em->persist($order);
                $em->flush();
                return $this->redirectToRoute('qs_booking_confirmation', ['orderId' => $order->getId()]);
            }
        }
        $em->persist($order);
        $em->flush();
        return $this->render('QSBookingBundle:Booking:checkout.html.twig', [
            'event' => $order->getEvent(),
            'order' => $order,
            'reservations' => $order->getReservations(),
        ]);
    }

    public function confirmationAction(Request $request, $orderId)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('QSBookingBundle:Order')->find($orderId);
        if (!$order) throw $this->createNotFoundException("La commande n'existe pas");
        $bookingService = $this->get('qs_booking.bookingService');
        if ($bookingService->isCanceledOrder($order)) return $this->render('QSBookingBundle:Booking:order-canceled.html.twig');
        $validator = $this->get('validator');
        if (count($validator->validate($order)) > 0) return $this->redirectToRoute('qs_booking_information', ['orderId' => $order->getId()]);
        if ($order->getStatus() != Order::STATUS_PAID) return $this->redirectToRoute('qs_booking_checkout', ['orderId' => $order->getId()]);
        $message = (new \Swift_Message('Musée du Louvre - Confirmation de commande'))
            ->setFrom('contact@qanops.com')
            ->setTo($order->getEmail())
            ->setBody(
                $this->renderView('QSBookingBundle:Booking:Emails/order-confirmation.html.twig',['order' => $order]),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
        return $this->render('QSBookingBundle:Booking:confirmation.html.twig', [
            'event' => $order->getEvent(),
            'order' => $order,
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
