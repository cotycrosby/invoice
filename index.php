<?php

require_once(dirname(__FILE__).'/vendor/autoload.php');


use Symfony\Component\Console\Application;

$app = new Application;

$app->add(new App\InitCommand());
$app->add(new App\AddCommand());
$app->add(new App\GetCommand());

$app->run();