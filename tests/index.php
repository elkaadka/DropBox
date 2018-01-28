<?php

require __DIR__ . '/../vendor/autoload.php';

$uploadParameter = new \Kanel\DropBox\Parameters\UploadParameters();
$client = new \Kanel\DropBox\Client('your access token here');
$reponse = $client->upload(__DIR__ . '/uploadMe.txt', '/');


print_r($reponse);