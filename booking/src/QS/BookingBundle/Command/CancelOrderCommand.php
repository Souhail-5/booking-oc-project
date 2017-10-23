<?php

namespace QS\BookingBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CancelOrderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('booking:cancel-order')
            ->setDescription('Cancel order when expired.')
            ->setHelp('Cancel the order when it has expired ..')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bookingService = $this->getContainer()->get('qs_booking.bookingService');
        $bookingService->cancelExpiredOrders();
    }
}
