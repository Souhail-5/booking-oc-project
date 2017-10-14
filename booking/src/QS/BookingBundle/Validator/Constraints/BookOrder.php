<?php

namespace QS\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BookOrder extends Constraint
{
    public $message = "La date sélectionnée pour l'évenement n'est pas disponible.";

    public function validatedBy()
    {
        return 'qs_booking_bookOrder';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
