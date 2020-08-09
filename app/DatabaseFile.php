<?php

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseFile
{
	private static $dbName = '.invoice.db';

	private function __construct() {} 

	public static function create() {
		file_put_contents(self::$dbName, '');
	}

	public static function exists() {
		return file_exists(self::$dbName);
	}

	public static function get() {

		return getcwd(). DIRECTORY_SEPARATOR. self::$dbName;

	}

	public static function initialize() {

		return \Illuminate\Database\Capsule\Manager::schema()->create('entries', function($table) {
			$table->dateTime('date', 0);
			$table->float('hours');
			$table->string('description');
		});

	}

	public static function bootEloquent() {

		$capsule = new Capsule;
		$dbFile = self::get();

		if(!$dbFile) {
			die("Database File was not found. Please try again.");
		}

		$capsule->addConnection([
				'driver'    => 'sqlite',
				'database'  => $dbFile,
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
		]);


		// Make this Capsule instance available globally via static methods... (optional)
		$capsule->setAsGlobal();

		// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
		$capsule->bootEloquent();

	}

}