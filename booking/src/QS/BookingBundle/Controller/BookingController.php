<?php

namespace QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookingController extends Controller
{
    public function indexAction()
    {
        return $this->render('QSBookingBundle:Booking:index.html.twig');
    }
}
