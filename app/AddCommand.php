<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;



class AddCommand extends Command 
{
	protected static $defaultName = 'add';

	protected function configure() {
		$this->setDescription('Logs hours and a message to a database file.');
		$this->setDefinition(
			new InputDefinition([
				new InputArgument('hours', InputArgument::REQUIRED, 'How much time was spent on the task (in hours:DECIMAL)'),
				new InputArgument('description', InputArgument::OPTIONAL, 'A short description of what you did.')
			])
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		if(!DatabaseFile::bootEloquent()) {
			$output->writeln('<fg=red;>Could not load database. Double check to make sure you\'re in the correct directory, or use invoice init</>');
			return Command::FAILURE;
		}

		$entry = new \Model\Entry;
		$entry->date = date('Y-m-d');
		$entry->hours = $input->getArgument('hours');
		$entry->description = $input->getArgument('description');

		if($entry->save() ){
			$output->writeln("<fg=cyan>Added the entry: {$input->getArgument('hours')} => {$input->getArgument('description')}</>");
			return Command::SUCCESS;
		}
		else {
			$output->writeln('<fg=red>Could not add entry</>');
			return Command::FAILURE;
		}
		
	}
}