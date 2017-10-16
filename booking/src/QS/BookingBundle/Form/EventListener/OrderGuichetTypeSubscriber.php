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
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_SUBMIT => 'submit',
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $order = $event->getData();

        if (null === $order) return;

        $event->getForm()->add('eventDate', FT\DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'data-toggle' => 'datepicker',
                'format' => 'yyyy-MM-dd',
                'class' => 'hide',
            ],
            'model_timezone' => $order->getEvent()->getTimeZone(),
            'view_timezone' => $order->getEvent()->getTimeZone(),
            'invalid_message' => 'La date indiquée est erronée',
            'data' => new \DateTime(null, new \DateTimeZone($order->getEvent()->getTimeZone())),
            'error_bubbling' => true,
        ]);
    }

    public function submit(FormEvent $event)
    {
        $form = $event->getForm();
        $order = $event->getData();

        if (null === $order) return;

        foreach ($form->get('tickets') as $ticket) {
            $order->setQtyResv($order->getQtyResv() + $ticket->get('qty')->getData());
        }

        if ($order->getQtyResv() <= 0 || $order->getQtyResv() >= 20) $form->addError(new FormError('Le nombre total de billet doit être compris entre 1 et 20.'));
    }
}
