<?php

namespace App\Command;


use App\Enum\NotificationTypeEnum;
use App\Repository\BookingRepository;
use App\Service\Emailing;
use App\Service\NotificationService;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'send:remind_email',
    description: 'Add a short description for your command',
)]
class ReminderReservationCommand extends Command
{
    public function __construct(
        private readonly BookingRepository   $bookingRepository,
        private readonly NotificationService $notificationService,
        private readonly Emailing            $emailing

    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int
    {
        // remind mail pour les bookings
        $usersBooking = $this->bookingRepository->getUserBookingStartIn2Days();
        $progressBar = new ProgressBar($output, count($usersBooking));
        $slug = NotificationTypeEnum::RESERVATION_REMINDER;
        foreach ($usersBooking as $userBooking) {
            $user = $userBooking->getUser();
            $this->notificationService->sendNotification(
                emailing: $this->emailing,
                sender: $user,
                slug: $slug,
                otherInfo: ['startDate' => $userBooking->getStartDate()->format('d/m/Y'), 'endDate' => $userBooking->getEndDate()->format('d/m/Y')]
            );
            $progressBar->advance();
        }
        $progressBar->finish();
        return Command::SUCCESS;
    }
}
