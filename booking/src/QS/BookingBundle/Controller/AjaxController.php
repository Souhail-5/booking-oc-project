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
        dump('ok');
        $em = $this->getDoctrine()->getManager();
        $sPeriod = $this->get('qs_booking.period');
        $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug($slug);

        if ('POST' !== $request->getMethod() || !$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Sorry not existing');
        }

        return new JsonResponse($sPeriod->getUnavailabilityForEvent($event));
    }
}
