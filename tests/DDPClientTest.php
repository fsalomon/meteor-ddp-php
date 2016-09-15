<?php
namespace synchrotalk\MeteorDDP\tests;
require 'vendor/autoload.php';
use synchrotalk\MeteorDDP\DDPClient;

$client = new DDPClient('localhost', 3000);
$client->connect();
$client->call("foo", array(1));
while(($yo = $client->getResult("foo")) === null) { sleep(1);};
echo 'Result = ' . $yo . PHP_EOL;

function resultHandler($a) {
    echo 'Result = ' . $a . PHP_EOL;
}


$client->asyncCall("foo", array(1), function($a) {
    resultHandler($a);
});
echo 'Do some work...' . PHP_EOL;

$client->asyncCall("foo", array(1), 'synchrotalk\MeteorDDP\tests\resultHandler');
echo 'Do some work...' . PHP_EOL;

$client->stop();
