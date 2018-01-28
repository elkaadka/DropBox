<?php

require __DIR__ . '/../vendor/autoload.php';

$uploadParameter = new \Kanel\DropBox\Parameters\UploadParameters();
$client = new \Kanel\DropBox\Client('v1B5ZBB_KlcAAAAAAAAMVzrhCkTrI_oH40LYFIq4d4EsWTNGQfpX38_sv5FcU_eM');
$reponse = $client->upload(__DIR__ . '/uploadMe.txt', '/');
$reponse = $client->getTemporaryLink($reponse->getPathDisplay());


print_r($reponse);