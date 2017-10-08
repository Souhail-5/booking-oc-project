<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\EventTicket;

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

    public function guichetAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);

        return $this->render('QSBookingBundle:Booking:guichet.html.twig', [
            'event' => $event,
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
