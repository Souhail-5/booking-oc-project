<?php

namespace QS\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookTicketValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function validate($tickets, Constraint $constraint) {
        if (count($this->em->getRepository('QSBookingBundle:Ticket')->getAllByIds($tickets)) != count($tickets)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
