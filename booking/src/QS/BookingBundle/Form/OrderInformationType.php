<?php

namespace QS\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FT;

class OrderInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('email')
            ->remove('eventDate')
            ->remove('tickets')
            ->add('reservations', FT\CollectionType::class, [
                'entry_type' => ReservationType::class,
            ])
        ;
    }

    public function getParent()
    {
        return OrderGuichetType::class;
    }
}
