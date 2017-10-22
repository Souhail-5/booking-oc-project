<?php

namespace QS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('QSCoreBundle:Default:index.html.twig');
    }
}
