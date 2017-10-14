<?php

namespace QS\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use QS\BookingBundle\Service\PeriodService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookOrderValidator extends ConstraintValidator
{
    private $requestStack;
    private $em;
    private $periodService;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, PeriodService $periodService) {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->periodService = $periodService;
    }

    public function validate($order, Constraint $constraint) {
        $request = $this->requestStack->getCurrentRequest();

        $totalResvDate = $this->em
            ->getRepository('QSBookingBundle:Event')
            ->getTotalQtyResvByEventDate($order->getEvent(), $order->getEventDate())
        ;

        if (
            !$this->periodService->isDateMatchEvent($order->getEventDate(), $order->getEvent())
            || ($totalResvDate + $order->getQtyResv()) > $order->getEvent()->getMaxResvDay()
        ) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
