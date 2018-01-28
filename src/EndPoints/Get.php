<?php

namespace Kanel\DropBox\EndPoints;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Kanel\DropBox\Client as DropBox;
use Kanel\DropBox\Exceptions\DropBoxException;

/**
 * @property string $accessToken
 * @property Client $guzzle
 * Trait Get
 * @package Kanel\DropBox\EndPoints
 */
trait Get
{
    /**
     * @param string $id
     * @throws DropBoxException
     */
    public function get(string $id)
    {
        try {
            $response = $this->guzzle->post(
                DropBox::URL_FILE_REQUEST_GET,
                [
                    'headers' => [
                        'Authorization'   => 'Bearer ' . $this->accessToken,
                        'Content-Type'    => 'application/json',
                    ],
                    RequestOptions::BODY => json_encode(['id' => $id ]),
                ]
            );
        } catch (RequestException $exception) {
            throw new DropBoxException($exception->getMessage(), $exception->getCode());
        }

        echo $response->getBody();
    }
}