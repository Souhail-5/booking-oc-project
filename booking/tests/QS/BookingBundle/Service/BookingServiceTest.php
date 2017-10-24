<?php

namespace Tests\QS\BookingBundle\Controller;

use PHPUnit\Framework\TestCase;
use QS\BookingBundle\Service\BookingService;
use QS\BookingBundle\Service\PeriodService;
use QS\BookingBundle\Entity\Event;
use QS\BookingBundle\Entity\Order;
use QS\BookingBundle\Entity\Ticket;
use QS\BookingBundle\Entity\TicketPrice;
use QS\BookingBundle\Entity\Period;
use QS\BookingBundle\Entity\Reservation;
use QS\BookingBundle\Entity\Visitor;

class BookingServiceTest extends TestCase
{
    /**
     * @dataProvider dateMatchPeriod
     */
    public function testIsDateMatchPeriod($date, $period, $expect)
    {
        $p = new Period;
        $p->setType($period['type']);
        $p->setStart($period['start']);
        $p->setEnd($period['end']);
        $em = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $periodService = new PeriodService($em);
        $this->assertEquals($expect, $periodService->isDateMatchPeriod($date, $p));
    }

    public function dateMatchPeriod()
    {
        return [
            [
                new \DateTime('2016-12-31'),
                [
                    'type' => 'range-date',
                    'start' => '2017-01-01',
                    'end' => 'infinite',
                ],
                false,
            ],
            [
                new \DateTime('2020-05-01'),
                [
                    'type' => 'month-day_nbr',
                    'start' => '05-01',
                    'end' => '05-01',
                ],
                true,
            ],
            [
                new \DateTime('2020-10-19'),
                [
                    'type' => 'day',
                    'start' => 1,
                    'end' => 1,
                ],
                true,
            ],
            [
                new \DateTime('1995-05-05 14:00:00'),
                [
                    'type' => 'range-todaytime',
                    'start' => '14:00:00',
                    'end' => '19:00:00',
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider visitorPrice
     */
    public function testGetPriceFromOrderVisitor($visitor, $expect)
    {
        $event = new Event;
        $event->setTimeZone('Europe/Paris');
        $o = new Order;
        $o->setEvent($event);
        $o->setEventDate(new \DateTime('2017-10-10'));
        $v = new Visitor;
        $v->setBirthDate(new \DateTime($visitor['birthDate'], new \DateTimeZone($o->getEvent()->getTimeZone())));
        $v->setDiscount($visitor['discount']);
        $em = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $ps = $this
            ->getMockBuilder('QS\BookingBundle\Service\PeriodService')
            ->disableOriginalConstructor()
            ->getMock();
        $bookingService = new BookingService($em, $ps, 8, '', '');
        $this->assertEquals($expect, $bookingService->getPriceFromOrderVisitor($o, $v));
    }

    public function visitorPrice()
    {
        return [
            [
                [
                    'birthDate' => '2015-03-20',
                    'discount' => false,
                ],
                0,
            ],
            [
                [
                    'birthDate' => '2015-03-20',
                    'discount' => true,
                ],
                0,
            ],
            [
                [
                    'birthDate' => '2010-08-03',
                    'discount' => false,
                ],
                8,
            ],
            [
                [
                    'birthDate' => '2010-08-03',
                    'discount' => true,
                ],
                8,
            ],
            [
                [
                    'birthDate' => '1995-12-25',
                    'discount' => false,
                ],
                16,
            ],
            [
                [
                    'birthDate' => '1995-12-25',
                    'discount' => true,
                ],
                10,
            ],
            [
                [
                    'birthDate' => '1950-04-17',
                    'discount' => false,
                ],
                12,
            ],
            [
                [
                    'birthDate' => '1950-04-17',
                    'discount' => true,
                ],
                10,
            ],
        ];
    }
}
