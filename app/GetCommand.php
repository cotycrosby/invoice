<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;

class GetCommand extends Command 
{
	protected static $defaultName = 'get';

	protected function configure() {
		$this->setDescription('Obtains all records for a given month and year ');
		$this->setDefinition(
			new InputDefinition([
				new InputArgument('month', InputArgument::OPTIONAL, 'What month are you looking to see?'),
				new InputArgument('year', InputArgument::OPTIONAL, 'What year?'),
				new InputArgument('rate', InputArgument::OPTIONAL, 'How much you make an hour')
			])
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		
		if(!DatabaseFile::bootEloquent()) {
			$output->writeln('<fg=red;>Could not load database. Double check to make sure you\'re in the correct directory, or use invoice init</>');
			return Command::FAILURE;
		}

		// setting the defaults for month and year
		// $month = ($month === -1) ? date('m') : $month;
		// $year  = ($year === -1 ) ? date('Y') : $year;
		$month = $input->getArgument('month') ?? date('m');
		$year = $input->getArgument('year') ?? date('Y');

		// Getting the first and last date for the given month/year
		$firstOfMonth = "$year-$month-01";
		$lastOfMonth = date('Y-m-t', strtotime($firstOfMonth));

		$entries = \Model\Entry::where('date', '>=', $firstOfMonth)->where('date', '<=', $lastOfMonth)->get();

		if(count($entries) === 0 ) {
			$output->writeln('<fg=yellow;>No entries have been added for this month</>');
			return Command::FAILURE;
		}

		$output->writeln("\nInvoice for <fg=blue;>$firstOfMonth</> to <fg=blue;>$lastOfMonth</>\n");

		$output->writeln("Hours \t Description");

		$totalHours = 0;
		foreach($entries as $entry) {

			$totalHours += $entry->hours; // incrementing the total
			$output->writeln( "{$entry->hours} \t <fg=cyan>{$entry->description}</>");
		}
		
		// Outputting Totals
		$output->writeln("\n<fg=green;>Total Hours: $totalHours</>");
		
		if($input->getArgument('rate')){
			$total = $input->getArgument('rate') * $totalHours;
			$total = number_format($total, 2);
			$output->writeln('<fg=green;>Total: $'. $total. '</>');
		}

		return Command::SUCCESS;
	}
}