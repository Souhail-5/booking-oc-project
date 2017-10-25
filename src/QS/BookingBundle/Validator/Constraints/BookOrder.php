<?php

namespace QS\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BookOrder extends Constraint
{
    public $message = "Impossible de continuer la commande. Veuillez réessayer.";

    public function validatedBy()
    {
        return 'qs_booking_bookOrder';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
