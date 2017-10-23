<?php

namespace QS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('QSCoreBundle:Default:index.html.twig');
    }

    public function cgvAction()
    {
        return $this->render('QSCoreBundle:Default:cgv.html.twig');
    }

    public function cguAction()
    {
        return $this->render('QSCoreBundle:Default:cgu.html.twig');
    }

    public function legalAction()
    {
        return $this->render('QSCoreBundle:Default:mentions-legales.html.twig');
    }
}
