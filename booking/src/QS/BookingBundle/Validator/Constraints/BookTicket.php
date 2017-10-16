<?php

namespace QS\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BookTicket extends Constraint
{
    public $message = "Vous devez sélectionner vos billets.";

    public function validatedBy()
    {
        return 'qs_booking_bookTicket';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
