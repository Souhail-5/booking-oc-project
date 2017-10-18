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

        if (!$this->periodService->isDateMatchEvent($order->getEventDate(), $order->getEvent())) {
            $this->context
                ->buildViolation("La date choisie n'est pas disponible pour cet évenement.")
                ->addViolation()
            ;
        }

        if (($totalResvDate + $order->getQtyResv()) > $order->getEvent()->getMaxResvDay()) {
            $this->context
                ->buildViolation("Malheureusement tous les billets ont été vendus pour cette date. Merci de choisir une autre date si possible.")
                ->addViolation()
            ;
        }
    }
}
