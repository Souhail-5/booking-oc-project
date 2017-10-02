<?php

namespace QS\BookingBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\EventPeriod;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\Price;
use QS\BookingBundle\Entity\TicketPrice;
use QS\BookingBundle\Entity\TicketPeriod;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 1 event
        $event = new Event;
        $event->setSlug('visite-musee-louvre');
        $event->setName('Visite du Musée du Louvre');
        $event->setMaxResvDay(1000);
        $event->setTimeZone('Europe/Paris');
        $manager->persist($event);

        // create periods
        // 0
        $period0 = new Period;
        $period0>setType('date');
        $period0>setStart('2017-01-01');
        $period0>setEnd('infinite');
        $manager->persist($period0);

        // 1
        $period1 = new Period;
        $period1>setType('date');
        $period1>setStart('2017-06-28');
        $period1>setEnd('infinite');
        $manager->persist($period1);

        // 2
        $period2 = new Period;
        $period2>setType('day');
        $period2>setStart('tuesday');
        $period2>setEnd('tuesday');
        $manager->persist($period2);

        // 3
        $period3 = new Period;
        $period3>setType('day');
        $period3>setStart('sunday');
        $period3>setEnd('sunday');
        $manager->persist($period3);;

        // 4
        $period4 = new Period;
        $period4>setType('month-day_nbr');
        $period4>setStart('05-01');
        $period4>setEnd('05-01');
        $manager->persist($period4);;

        // 5
        $period5 = new Period;
        $period5>setType('month-day_nbr');
        $period5>setStart('11-01');
        $period5>setEnd('11-01');
        $manager->persist($period5);;

        // 6
        $period6 = new Period;
        $period6>setType('month-day_nbr');
        $period6>setStart('12-25');
        $period6>setEnd('12-25');
        $manager->persist($period6);

        // 7
        $period7 = new Period;
        $period7>setType('month-day_nbr');
        $period7>setStart('12-25');
        $period7>setEnd('12-25');
        $manager->persist($period7);

        // 8
        $period8 = new Period;
        $period8>setType('today');
        $period8>setStart('14:00:00');
        $period8>setEnd('23:59:59');
        $manager->persist($period8);

        // Add prices
        // 1
        $price1 = new Price;
        $price1->setName('normal');
        $price1->setEur(16);
        $manager->persist($price1);

        // 2
        $price2 = new Price;
        $price2->setName('enfant');
        $price2->setEur(8);
        $manager->persist($price2);

        // 3
        $price3 = new Price;
        $price3->setName('senior');
        $price3->setEur(12);
        $manager->persist($price3);

        // 4
        $price4 = new Price;
        $price4->setName('réduit');
        $price4->setEur(10);
        $manager->persist($price4);

        // 5
        $price5 = new Price;
        $price5->setName('gratuit');
        $price5->setEur(0);
        $manager->persist($price5);

        // Create event period
        $event_period_numbers = [1, 2, 4, 5, 6, 7];
        foreach ($event_period_numbers as $nbr) {
            ${'eventPeriod'.$nbr} = new EventPeriod;
            if ($nbr == 1) {
                ${'eventPeriod'.$nbr}>setAction(${'eventPeriod'.$nbr}::ACTION_INCLUDE);
            } else {
                ${'eventPeriod'.$nbr}>setAction(${'eventPeriod'.$nbr}::ACTION_EXCLUDE);
            }
            ${'eventPeriod'.$nbr}>setEvent($event);
            ${'eventPeriod'.$nbr}>setPeriod(${'period'.$nbr});
            $manager->persist(${'eventPeriod'.$nbr});
        }

        // Add journée ticket
        $ticket1 = new Ticket;
        $ticket1->setName('journée');
        $manager->persist($ticket1);

        $ticket1_price_numbers = [1, 2, 3, 4, 5];
        foreach ($ticket1_price_numbers as $nbr) {
            ${'ticket1Price'.$nbr} = new TicketPrice;
            ${'ticket1Price'.$nbr}->setTicket($ticket1);
            ${'ticket1Price'.$nbr}->setPrice(${'price'.$nbr});
            $manager->persist(${'ticket1Price'.$nbr});
        }

        $ticket1_period_numbers = [0, 3, 8];
        foreach ($ticket1_period_numbers as $nbr) {
            ${'ticket1Period'.$nbr} = new TicketPeriod;
            if ($nbr == 0) ${'ticket1Period'.$nbr}>setAction(${'ticket1Period'.$nbr}::ACTION_INCLUDE);
            if ($nbr == 3) ${'ticket1Period'.$nbr}>setAction(${'ticket1Period'.$nbr}::ACTION_EXCLUDE);
            if ($nbr == 8) ${'ticket1Period'.$nbr}>setAction(${'ticket1Period'.$nbr}::ACTION_EXCLUDE);
            ${'ticket1Period'.$nbr}>setTicket($ticket1);
            ${'ticket1Period'.$nbr}>setPeriod(${'period'.$nbr});
            $manager->persist(${'ticket1Period'.$nbr});
        }

        // Add demi-journée ticket
        $ticket2 = new Ticket;
        $ticket2->setName('demi-journée');
        $manager->persist($ticket2);

        $ticket2_price_numbers = [1, 2, 3, 4, 5];
        foreach ($ticket2_price_numbers as $nbr) {
            ${'ticket2Price'.$nbr} = new TicketPrice;
            ${'ticket2Price'.$nbr}->setTicket($ticket2);
            ${'ticket2Price'.$nbr}->setPrice(${'price'.$nbr});
            $manager->persist(${'ticket2Price'.$nbr});
        }

        $ticket2_period_numbers = [0, 3, 8];
        foreach ($ticket2_period_numbers as $nbr) {
            ${'ticket2Period'.$nbr} = new TicketPeriod;
            if ($nbr == 0) ${'ticket2Period'.$nbr}>setAction(${'ticket2Period'.$nbr}::ACTION_INCLUDE);
            if ($nbr == 3) ${'ticket2Period'.$nbr}>setAction(${'ticket2Period'.$nbr}::ACTION_EXCLUDE);
            if ($nbr == 8) ${'ticket2Period'.$nbr}>setAction(${'ticket2Period'.$nbr}::ACTION_EXCLUDE);
            ${'ticket2Period'.$nbr}>setTicket($ticket2);
            ${'ticket2Period'.$nbr}>setPeriod(${'period'.$nbr});
            $manager->persist(${'ticket2Period'.$nbr});
        }

        // Flush all
        $manager->flush();
    }
}
