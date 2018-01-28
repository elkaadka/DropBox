<?php

namespace Kanel\DropBox\EndPoints;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\RequestOptions;
use Kanel\DropBox\Exceptions\DropBoxException;
use Kanel\DropBox\Client as DropBox;
use Kanel\DropBox\Parameters\UploadParameters;
use Kanel\DropBox\Responses\UploadResponse;
use Kanel\Mapper\Mapper;

/**
 * Trait Upload
 *
 * @property string $accessToken
 * @package Kanel\DropBox\EndPoints
 */
trait Upload
{
    /**
     * Create a new file with the contents provided in the first param in a dropbox directory specified in param 2.
     * If the file is <= 150Mb, it will be uploaded using https://www.dropbox.com/developers/documentation/http/documentation#files-upload
     *
     * If the file is > 150Mb an upload session is automatically created using :
     *     https://www.dropbox.com/developers/documentation/http/documentation#files-upload_session-start
     *     https://www.dropbox.com/developers/documentation/http/documentation#files-upload_session-append_v2
     *     https://www.dropbox.com/developers/documentation/http/documentation#files-upload_session-finish
     *
     * @param string $file_source the file on your disk to upload to dropbox (absolute path)
     * @param string $folder_destination (the folder destination, defaults to '/' if none sent)
     * @param UploadParameters|null $parameters a list of parameters to customize the upload
     *
     * @return UploadResponse
     *
     * @throws DropBoxException
     */
    public function upload(string $file_source, string $folder_destination = '/', UploadParameters $parameters = null)
    {
        if (!file_exists($file_source)) {
            throw new DropBoxException('file '.$file_source.' not found');
        }

        $parameters = $parameters ?? new UploadParameters();
        $newName = $parameters->getNewName() ?? basename($file_source);
        $dropBoxFolder = rtrim($folder_destination, '/');

        // Limit the size of the body to 100Mb and start reading from byte 0
        $original = Psr7\stream_for(fopen($file_source, 'r+'));

        if ($original->getSize() <= DropBox::URL_UPLOAD_CHUNK_MAX_SIZE) {

            $stream = new Psr7\LimitStream($original,  $original->getSize(), 0);

            try {
                $args = [
                    "path"       => $dropBoxFolder . '/' . $newName,
                    "mode"       => $parameters->getWriteMode(),
                    "autorename" => $parameters->isAutoRenameFile(),
                    "mute"       => $parameters->isMute()
                ];
                if ($parameters->getClientModified()) {
                    $args["client_modified"] = $parameters->getClientModified();
                }
                $response = $this->guzzle->post(
                    DropBox::URL_FILE_UPLOAD,
                    [
                        'headers' => [
                            'Authorization'   => 'Bearer ' . $this->accessToken,
                            'Dropbox-API-Arg' => json_encode($args),
                            'Content-Type'    => 'application/octet-stream',
                        ],

                        RequestOptions::BODY => $stream,
                    ]
                );
            } catch (RequestException $exception) {
                throw new DropBoxException($exception->getMessage(), $exception->getCode());
            }

        } else {

            $stream = new Psr7\LimitStream($original,  $parameters->getChunksSize(), 0);

            try {
                $response = $this->guzzle->post(
                    DropBox::URL_UPLOAD_START_SESSION,
                    [
                        'headers' => [
                            'Authorization'   => 'Bearer ' . $this->accessToken,
                            'Dropbox-API-Arg' => json_encode(['close' => false]),
                            'Content-Type'    => 'application/octet-stream',
                        ],

                        RequestOptions::BODY => $stream,
                    ]
                );
            } catch (RequestException $exception) {
                throw new DropBoxException($exception->getMessage(), $exception->getCode());
            }

            $bodyObject = json_decode($response->getBody());
            if (!isset($bodyObject->session_id)) {
                throw new DropBoxException('could not start upload session');
            }

            while($stream->read(DropBox::URL_UPLOAD_CHUNK_MAX_SIZE)) {
                try {
                    $this->guzzle->post(
                        DropBox::URL_UPLOAD_APPEND,
                        [
                            'headers' => [
                                'Authorization'   => 'Bearer ' . $this->accessToken,
                                'Dropbox-API-Arg' => json_encode(['close' => false]),
                                'Content-Type'    => 'application/octet-stream',
                            ],

                            RequestOptions::BODY => $stream,
                        ]
                    );
                } catch (RequestException $exception) {
                    throw new DropBoxException($exception->getMessage(), $exception->getCode());
                }

            }

            try {
                $response = $this->guzzle->post(
                    DropBox::URL_UPLOAD_FINISH_SESSION,
                    [
                        'headers' => [
                            'Authorization'   => 'Bearer ' . $this->accessToken,
                            'Dropbox-API-Arg' => json_encode(
                                [
                                    'cursor' => [
                                        'session_id' => $bodyObject->session_id,
                                        'offset'     => $stream->tell()
                                    ],
                                    'commit' => [
                                        "path"       => $dropBoxFolder . '/' . $newName,
                                        "mode"       => $parameters->getWriteMode(),
                                        "autorename" => $parameters->isAutoRenameFile(),
                                        "mute"       => $parameters->isMute(),
                                    ]
                                ]
                            ),
                            'Content-Type'    => 'application/octet-stream',
                        ],

                        RequestOptions::BODY => $stream,
                    ]
                );
            } catch (RequestException $exception) {
                throw new DropBoxException($exception->getMessage(), $exception->getCode());
            }
        }


        $uploadResponse = new UploadResponse();
        $mapper = new Mapper();
        $mapper->map(json_decode($response->getBody()), $uploadResponse);

        return $uploadResponse;
    }
}

