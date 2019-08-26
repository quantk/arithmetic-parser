<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$interpreter = new \App\Interpreter('2+2');
$interpreter->expr();