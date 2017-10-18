<?php

namespace QS\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FT;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use QS\BookingBundle\Validator\Constraints\BookTicket;

class OrderGuichetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $order = $builder->getData();
        $builder
            ->add('email', FT\EmailType::class, [
                'error_bubbling' => true,
                'attr' => [
                    'placeholder' => 'email@lorem.com',
                ]
            ])
            ->add('eventDate', FT\DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'data-toggle' => 'datepicker',
                    'format' => 'yyyy-MM-dd',
                    'class' => 'hide',
                ],
                'data' => new \DateTime(null, new \DateTimeZone($order->getEvent()->getTimeZone())),
                'model_timezone' => $order->getEvent()->getTimeZone(),
                'view_timezone' => $order->getEvent()->getTimeZone(),
                'invalid_message' => "La date fournie \"{{ value }}\" n'est pas au bon format et n'a pas pu être évaluée.",
                'error_bubbling' => true,
            ])
            ->add('tickets', FT\CollectionType::class, [
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'label' => false,
                'constraints' => [
                    new BookTicket(),
                ],
                'error_bubbling' => true,
            ])
        ;
        $builder->addEventSubscriber(new EventListener\OrderGuichetTypeSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'QS\BookingBundle\Entity\Order'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'qs_bookingbundle_order';
    }


}
