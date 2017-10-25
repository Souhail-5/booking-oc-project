<?php

namespace QS\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use QS\BookingBundle\Service\PeriodService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookTicketValidator extends ConstraintValidator
{
    private $em;
    private $periodService;

    public function __construct(EntityManagerInterface $em, PeriodService $periodService) {
        $this->em = $em;
        $this->periodService = $periodService;
    }

    public function validate($tickets, Constraint $constraint) {
        if ((count($this->em->getRepository('QSBookingBundle:Ticket')->getAllByIdsEvent($tickets, $constraint->order->getEvent()))
            != count($tickets))
            && $this->periodService->isDateMatchTickets($constraint->order->getEventDate(), $tickets)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
