<?php
namespace Cobase\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cobase:notifications:send')
            ->setDescription('Sends email notifications regarding new posts');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notificationService = $this->getContainer()->get('cobase_app.service.notification');
        $amount = $notificationService->notifyOfNewPosts($this->getContainer()->get('cobase.mailTemplate.newPost'), 20);

        $output->writeln("Sent $amount emails");
    }
}
