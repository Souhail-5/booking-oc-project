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
    public function rootAction(Request $request)
    {
        $method = $request->request->get('action').'Action';
        if (!$request->isMethod('POST')
            || !$request->isXmlHttpRequest()
            || !$method
            || method_exists($this, $method) === false
        ) throw $this->createNotFoundException('Sorry not existing');
        return $this->$method($request);
    }

    public function getUnavailablePeriodsByEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $slug = $request->request->get('eventSlug');
        $periodService = $this->get('qs_booking.periodService');
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);
        return new JsonResponse($periodService->getUnavailablePeriodsByEvent($event));
    }

    public function getTicketsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $date = $request->request->get('date');
        $slug = $request->request->get('eventSlug');
        $bookingService = $this->get('qs_booking.bookingService');
        $periodService = $this->get('qs_booking.periodService');
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);
        $date = (new \Datetime(null, new \DateTimeZone($event->getTimeZone())))->modify($date);
        if (!$periodService->isDateMatchEvent($date, $event)) return new JsonResponse(false);
        if ($bookingService->isFullEventDate($event, $date)) return new JsonResponse(0);
        $tickets = [];
        foreach ($bookingService->getAvailableTicketsByEventDate($event, $date) as $key => $ticket) {
            $tickets[$key]['id'] = $ticket->getId();
            $tickets[$key]['name'] = $ticket->getName();
            $tickets[$key]['description'] = $ticket->getDescription();
        }
        return new JsonResponse($tickets);
    }
}
