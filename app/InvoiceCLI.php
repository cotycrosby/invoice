<?php

namespace App;

use \splitbrain\phpcli\{CLI, Options, TableFormatter, Colors};

class InvoiceCLI extends CLI 
{
	// CLI Library setup.
	protected function setup(Options $options) {
		$options->setHelp("A very Minimal example that does nothing but print a version");

		$options->registerCommand('init', 'Initalize the database');
		$options->registerCommand('add', 'Add an entry to the database');
		$options->registerCommand('get', 'Get information for the database');

		//invoice add hours arguments.
		$options->registerArgument('time', 'The hours spent on the task (decimal)', true, 'add');
		$options->registerArgument('message', 'Description of what was done', false, 'add');

		// invoice get argument.
		$options->registerArgument('month', 'Which month do you wish to get: (1-12)', false, 'get');
		$options->registerArgument('year', 'Which year do you wish to get: (ex: 2020)', false, 'get');
	}

	// Main CLI handling.
	protected function main(Options $options) {

		// Setting the default parameters to a sentinel value of -1
		$args = $options->getArgs();
		$firstParam = $args[0] ?? -1;
		$secondParam = $args[1] ?? -1;

		switch($options->getCmd() ){
			case 'init':
				$this->initializeDatabase();
				break;
			case 'add':
				$this->addEntry($firstParam, $secondParam);
				break;
			case 'get':
				$this->getEntries($firstParam, $secondParam);
				break;
			default:
				echo $options->help();
		}
	}
	
	private function initializeDatabase() {
		if(DatabaseFile::exists()){
			$this->error('The database file already exists. If you initialize it it will wipe the data. (Delete the file if you wish to proceed)');
			return;
		}

		DatabaseFile::create();
		DatabaseFile::bootEloquent();

		if( DatabaseFile::initialize() ) {
			$this->success('Database created successfully!');
		}
		else {
			$this->error('Something went wrong...');
		}
	}

	private function addEntry($hours, $description) {

		DatabaseFile::bootEloquent();

		// Converting message from sentinel to ''
		$description = ($description === -1 ) ? '' : $description;

		$entry = new \Model\Entry;
		$entry->date = date('Y-m-d');
		$entry->hours = $hours;
		$entry->description = $description;

		if($entry->save() ){
			$this->info("Added the entry: $hours => $description");
		}
		else {
			$this->error('Could not add entry');
		}
		
		
	}

	private function getEntries($month, $year) {

		DatabaseFile::bootEloquent();

		// setting the defaults for month and year
		$month = ($month === -1) ? date('m') : $month;
		$year  = ($year === -1 ) ? date('Y') : $year;

		// Getting the first and last date for the given month/year
		$firstOfMonth = "$year-$month-01";
		$lastOfMonth = date('Y-m-t', strtotime($firstOfMonth));

		$entries = \Model\Entry::where('date', '>=', $firstOfMonth)->where('date', '<=', $lastOfMonth)->get();

		if(count($entries) === 0 ) {
			$this->warning('No entries have been added for this month');
			return;
		}

		echo "\nInvoice for $firstOfMonth to $lastOfMonth\n\n";

		echo "Hours \tDescription\n";

		$totalHours = 0;
		foreach($entries as $entry) {

			$totalHours += $entry->hours; // incrementing the total
			echo "{$entry->hours} \t {$entry->description}\n";
		}
		
		// Outputting Totals
		echo "\n";
		$this->success('Total Hours: '.$totalHours);



	}

}