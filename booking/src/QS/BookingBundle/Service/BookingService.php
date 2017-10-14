<?php

namespace QS\BookingBundle\Service;

use Doctrine\ORM\EntityManager;

class BookingService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}
