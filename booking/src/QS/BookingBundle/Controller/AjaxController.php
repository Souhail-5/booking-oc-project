<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\EventTicket;

class AjaxController extends Controller
{
    public function getUnavailabilityForEventAction(Request $request, $slug)
    {
        if ('POST' !== $request->getMethod() || !$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Sorry not existing');
        }

        $em = $this->getDoctrine()->getManager();
        $sPeriod = $this->get('qs_booking.periodService');
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);

        return new JsonResponse($sPeriod->getUnavailabilityForEvent($event));
    }

    public function getAvailableTicketsByEventDateAction(Request $request, $slug, $date)
    {
        if ('POST' !== $request->getMethod() || !$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Sorry not existing');
        }

        $em = $this->getDoctrine()->getManager();
        $sPeriod = $this->get('qs_booking.periodService');
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);
        $eventTickets = $event->getTickets();

        $date = (new \Datetime(null, new \DateTimeZone($event->getTimeZone())))->modify($date);
        $availableTickets = [];
        foreach ($eventTickets as $ticket) {
            $ticketExcludedPeriods = $em->getRepository('QSBookingBundle:Period')->getExcludedPeriodByTicket($ticket);
            foreach ($ticketExcludedPeriods as $period) {
                if ($sPeriod->isDateMatchPeriod($date, $period)) {
                    continue 2;
                }
            }
            $availableTickets[] = $ticket;
        }
        $tickets = [];
        foreach ($availableTickets as $key => $ticket) {
            $tickets[$key]['id'] = $ticket->getId();
            $tickets[$key]['name'] = $ticket->getName();
        }

        return new JsonResponse($tickets);
    }
}
