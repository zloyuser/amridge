<?php

use Amp\Loop;
use PHPinnacle\Amridge\Connection;
use PHPinnacle\Amridge\RPC;

require "vendor/autoload.php";

Loop::run(function () {
    $connection = new Connection('tcp://127.0.0.1:6001');

    yield $connection->open(0, 0, false);

    $rpc = new RPC($connection);
    
    $time = microtime(true);

    $promises = [];

    for ($i = 0; $i < 100; ++$i) {
        $promises[] = $rpc->call("App.Hi", "RPC {$i}");
    }

    $r = yield $promises;

    foreach ($r as $v) {
        echo $v . \PHP_EOL;
    }

    echo microtime(true) - $time . \PHP_EOL;

    $connection->close();
});
