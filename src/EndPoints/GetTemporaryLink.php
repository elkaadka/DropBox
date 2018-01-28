<?php

namespace Kanel\DropBox\EndPoints;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Kanel\DropBox\Client as DropBox;
use Kanel\DropBox\Exceptions\DropBoxException;
use Kanel\DropBox\Parameters\GetTemporaryLinkParameters;
use Kanel\Mapper\Mapper;

/**
 * @property string $accessToken
 * @property Client $guzzle
 * Trait GetTemporaryLink
 */
trait GetTemporaryLink
{
    /**
     * @param string $filePath
     * @return GetTemporaryLinkParameters
     * @throws DropBoxException
     */
    public function getTemporaryLink(string $filePath)
    {
        try {
            $response = $this->guzzle->post(
                DropBox::URL_GET_TEMPORARY_LINK,
                [
                    'headers' => [
                        'Authorization'   => 'Bearer ' . $this->accessToken,
                        'Content-Type'    => 'application/json',
                    ],
                    RequestOptions::BODY => json_encode(['path' => $filePath ]),
                ]
            );
        } catch (RequestException $exception) {
            throw new DropBoxException($exception->getMessage(), $exception->getCode());
        }

        $parameters = new GetTemporaryLinkParameters();
        $mapper = new Mapper();
        $mapper->map(json_decode($response->getBody()), $parameters);

        return $parameters;
    }
}