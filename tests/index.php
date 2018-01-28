<?php

require __DIR__ . '/../vendor/autoload.php';

$uploadParameter = new \Kanel\DropBox\Parameters\UploadParameters();
$client = new \Kanel\DropBox\Client('access_token');
$reponse = $client->upload(__DIR__ . '/uploadMe.txt', '/');
$reponse = $client->getTemporaryLink($reponse->getPathDisplay());


print_r($reponse);