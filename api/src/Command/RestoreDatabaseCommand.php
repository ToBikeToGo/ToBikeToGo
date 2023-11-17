<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'doctrine:database:restore',
    description: 'Restore the database',
)]
class RestoreDatabaseCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('database', 'd', InputOption::VALUE_OPTIONAL, 'The database name', 'tobiketogo')
            ->setHelp('This command allows you to restore the database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Restore Database');

        $database = $input->getOption('database');
        $backupFile = $database . '_backup.sql';

        $io->progressStart(100);

        if (!shell_exec('psql --version')) {
            $io->error('psql is not installed.');

            return Command::FAILURE;
        }

        $databaseCommand = "psql -lqt | cut -d \| -f 1 | grep -w $database";

        $io->text("Checking if database '$database' exists...");

        if (!shell_exec($databaseCommand)) {
            $io->error("Database '$database' does not exist.");

            return Command::FAILURE;
        }

        $io->text("Checking if backup file '$backupFile' exists...");

        if (!file_exists("/home/debian/backup/$backupFile")) {
            $io->error("Backup file '$backupFile' does not exist.");

            return Command::FAILURE;
        }

        $command = "psql $database < /home/debian/backup/$backupFile";

        $io->progressAdvance(50);

        $io->text("Restore in progress for database '$database'...");

        shell_exec($command);

        $io->progressFinish();

        $io->success("Restore completed for database '$database'.");

        return Command::SUCCESS;
    }
}
