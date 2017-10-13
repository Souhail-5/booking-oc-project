<?php

namespace QS\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FT;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class OrderGuichetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', FT\EmailType::class)
            ->add('tickets', FT\CollectionType::class, [
                'entry_type' => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'label' => false,
            ])
        ;
        $builder->addEventSubscriber(new EventListener\AddDateFieldToOrderSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'QS\BookingBundle\Entity\Order'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'qs_bookingbundle_order';
    }


}
