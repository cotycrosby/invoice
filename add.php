<?php 

require_once('./globals.php');
require_once('./classes/Debug.php');

Debug::enable();

Debug::echo('WERE ON STAGING NOWWW');

$data = [
	'name' => 'Coty',
	'age' => 'old as fuck',
	'dob' => '5-31-91'
];

Debug::dump($data);

Debug::print($data);

Debug::disable();
Debug::consoleLog($data);


Debug::consoleLog($data, true);




