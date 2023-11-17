<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'doctrine:database:backup',
    description: 'Backup the database',
)]
class BackupDatabaseCommand extends Command
{
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to backup the database.')
            ->addOption('database', 'd', InputOption::VALUE_OPTIONAL, 'The database name', 'tobiketogo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Backup Database');

        $database = $input->getOption('database');

        $backupFile = $database . '_backup.sql';

        $io->progressStart(100);

        if (!shell_exec('psql --version')) {
            $io->error('psql is not installed.');

            return Command::FAILURE;
        }

        if (!shell_exec('pg_dump --version')) {
            $io->error('pg_dump is not installed.');

            return Command::FAILURE;
        }

        $databaseCommand = "psql -lqt | cut -d \| -f 1 | grep -w $database";

        $io->text("Checking if database '$database' exists...");
        
        if (!shell_exec($databaseCommand)) {
            $io->error("Database '$database' does not exist.");
            
            return Command::FAILURE;
        }

        $command = "pg_dump -a $database > /home/debian/backup/$backupFile";

        $io->progressAdvance(20);

        $io->text("Backup in progress for database '$database'...");

        shell_exec($command);

        $io->progressAdvance(50);

        if (!file_exists("/home/debian/backup/$backupFile")) {
            $io->error("Backup file '$backupFile' not created.");

            return Command::FAILURE;
        }

        $io->text("Backup file created: '$backupFile' in /home/debian/backup/");
        $io->progressFinish();

        $io->success("Backup completed successfully for database '$database'.");

        return Command::SUCCESS;
    }
}
