<?php

namespace QS\BookingBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type as FT;

class OrderGuichetTypeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::SUBMIT => 'submit',
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
    }
}
