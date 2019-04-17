<?php
session_start();

require __DIR__ . "/vendor/autoload.php";

$config = [
    'domainName' => 'localhost',
    'steamKey' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
];

$steam = new Gabyfle\SteamAuth($config['domainName'], $config['steamKey']);
/* Opening a connection with Steam services and checking if user is connected*/
try{
    $steam->open(); // or $isConnected = $steam->open()->check();
    if ($steam->check() /* or directly $isConnected */) {
        $steam->getDataFromSteam();
        header('Location: index.php');
    } else
        echo 'A problem occurred while validating your connection.';
} catch(\ErrorException $e) {
    echo 'An error occurred : ' . $e;
}