<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command 
{
	protected static $defaultName = 'init';

	protected function configure() {
		$this->setDescription('Creates the SQLITE file in the current directory');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		if(DatabaseFile::exists()){
			$output->writeln('<fg=red;>The database file already exists. If you initialize it it will wipe the data.</>');
			$output->writeln('');
			$output->writeln('If you wish to proceed, run: <bg=red;fg=white;>rm .invoice.db</>');
			return Command::FAILURE;
		}

		DatabaseFile::create();
		DatabaseFile::bootEloquent();
		DatabaseFile::initialize();
		$output->writeln('<fg=green;>Database created successfully!</>');
		
		return Command::SUCCESS;
	}
}