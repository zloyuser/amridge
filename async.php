<?php

use Amp\Loop;
use PHPinnacle\Amridge\Connection;
use PHPinnacle\Amridge\RPC;

require "vendor/autoload.php";

Loop::run(function () {
    $connection = new Connection('tcp://127.0.0.1:6001');

    yield $connection->open(0, 0, false);

    $rpc = new RPC($connection);

    echo yield $rpc->call("App.Hi", "Antony");

    $connection->close();
});
