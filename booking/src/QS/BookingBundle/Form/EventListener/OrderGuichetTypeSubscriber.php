<?php

namespace QS\BookingBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type as FT;

class OrderGuichetTypeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'submit',
        ];
    }

    public function submit(FormEvent $event)
    {
        $form = $event->getForm();
        $order = $event->getData();

        if (null === $order) return;

        foreach ($form->get('tickets') as $ticket) {
            $order->setQtyResv($order->getQtyResv() + $ticket->get('qty')->getData());
            if ($order->getQtyResv() <= 0 || $order->getQtyResv() >= 20) $form->addError(new FormError('Le nombre total de billet doit être compris entre 1 et 20.'));
        }
    }
}
