<?php

require_once(dirname(__FILE__).'/vendor/autoload.php');

$invoiceCLI = new App\InvoiceCLI();

$invoiceCLI->run();