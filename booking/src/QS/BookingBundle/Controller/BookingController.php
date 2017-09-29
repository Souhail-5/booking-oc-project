<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\EventPeriod;

class BookingController extends Controller
{
    public function indexAction(Request $req)
    {
        $action = $req->query->get('action');
        $event = null;
        $periods = [];

        $em = $this->getDoctrine()->getManager();

        switch ($action) {
            case 'add-event':
                $event = new Event;
                $event->setSlug('visite-musee-louvre');
                $event->setName('Visite du Musée du Louvre');
                $event->setMaxResvDay(1000);
                $event->setTimeZone('Europe/Paris');

                $em->persist($event);
                $em->flush();
                break;

            case 'edit-event':
                $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug('visit-musee-louvre');
                $event->setSlug('visite-musee-louvre');

                $em->flush();
                break;

            case 'remove-event':
                $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug('visit-musee-louvre');
                $em->remove($event);

                $em->flush();
                break;

            case 'add-period':
                $period0 = new Period;
                $period0->setType('date');
                $period0->setStart('2017-06-28');
                $period0->setEnd('unlimited');
                $periods[] = $period0;

                $period1 = new Period;
                $period1->setType('day');
                $period1->setStart('tuesday');
                $period1->setEnd('tuesday');
                $periods[] = $period1;

                $period2 = new Period;
                $period2->setType('day');
                $period2->setStart('sunday');
                $period2->setEnd('sunday');
                $periods[] = $period2;

                $period3 = new Period;
                $period3->setType('month-day_nbr');
                $period3->setStart('05-01');
                $period3->setEnd('05-01');
                $periods[] = $period3;

                $period4 = new Period;
                $period4->setType('month-day_nbr');
                $period4->setStart('11-01');
                $period4->setEnd('11-01');
                $periods[] = $period4;

                $period5 = new Period;
                $period5->setType('month-day_nbr');
                $period5->setStart('12-25');
                $period5->setEnd('12-25');
                $periods[] = $period5;

                $em->persist($period0);
                $em->persist($period1);
                $em->persist($period2);
                $em->persist($period3);
                $em->persist($period4);
                $em->persist($period5);
                $em->flush();
                break;

            case 'remove-period':
                $periods = $em->getRepository('QSBookingBundle:Period')->findAll();
                foreach ($periods as $period) {
                    $em->remove($period);
                }

                $em->flush();
                break;

            case 'add-periods-to-event':
                $periods = $em->getRepository('QSBookingBundle:Period')->findAll();
                $eventPeriods = [];

                foreach ($periods as $period) {
                    $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug('visite-musee-louvre');
                    if ($period->getType() == 'date') {
                        $eventPeriods[] = new EventPeriod;
                        end($eventPeriods)->setAction('include');
                        end($eventPeriods)->setEvent($event);
                        end($eventPeriods)->setPeriod($period);
                    } else {
                        $eventPeriods[] = new EventPeriod;
                        end($eventPeriods)->setAction('exclude');
                        end($eventPeriods)->setEvent($event);
                        end($eventPeriods)->setPeriod($period);
                    }
                    $em->persist(end($eventPeriods));
                }

                $em->flush();
                break;

            case 'get-excluded-periods':
                $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug('visite-musee-louvre');
                $event = $em->getRepository('QSBookingBundle:Event')->getExcludedPeriods($event->getId());
                break;

            case 'get-included-periods':
                $event = $em->getRepository('QSBookingBundle:Event')->findOneBySlug('visite-musee-louvre');
                $event = $em->getRepository('QSBookingBundle:Event')->getIncludedPeriods($event->getId());
                break;

            default:
                break;
        }

        return $this->render('QSBookingBundle:Booking:guichet.html.twig', [
            'action' => $action,
            'event' => $event,
            'periods' => $periods,
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
