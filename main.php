<?php /** @noinspection PhpComposerExtensionStubsInspection */
require_once __DIR__ . '/vendor/autoload.php';


use App\Interpreter;

try {
    $line = null;
    while ($line !== 'exit') {
        $expr   = readline('Enter> ');
        $result = Interpreter::evaluate($expr);
        echo $result . PHP_EOL;
    }
} catch (\Throwable $e) {
    throw $e;
}
