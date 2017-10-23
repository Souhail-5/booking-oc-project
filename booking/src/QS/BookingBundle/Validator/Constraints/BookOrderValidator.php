<?php

namespace QS\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use QS\BookingBundle\Service\BookingService;
use QS\BookingBundle\Service\PeriodService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookOrderValidator extends ConstraintValidator
{
    private $requestStack;
    private $em;
    private $bookingService;
    private $periodService;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, BookingService $bookingService, PeriodService $periodService) {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->bookingService = $bookingService;
        $this->periodService = $periodService;
    }

    public function validate($order, Constraint $constraint) {
        $now = new \DateTime(null, new \DateTimeZone($order->getEvent()->getTimeZone()));
        if ($now->diff($order->getEventDate())->d < 0
            || !$this->periodService->isDateMatchEvent($order->getEventDate(), $order->getEvent())) {
            $this->context
                ->buildViolation("Cet évènement n'a pas lieu à la date sélectionnée, merci de choisir une autre date.")
                ->addViolation()
            ;
        }
        if ($this->bookingService->isFullEventDate($order->getEvent(), $order->getEventDate())) {
            $this->context
                ->buildViolation("Malheureusement tous les billets ont été vendus pour cette date. Merci de choisir une autre date si possible.")
                ->addViolation()
            ;
        }
    }
}
