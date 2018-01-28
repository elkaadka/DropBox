<?php

namespace Kanel\DropBox;

use Kanel\DropBox\EndPoints\Upload;

class Client
{
    protected $accessToken;
    protected $guzzle;

    const URL = 'https://content.dropboxapi.com/2';

    //Upload url end points
    const URL_FILE_UPLOAD = '/files/upload';
    const URL_UPLOAD_START_SESSION = '/files/upload_session/start';
    const URL_UPLOAD_APPEND = '/files/upload_session/append_v2';
    const URL_UPLOAD_FINISH_SESSION = '/files/upload_session/finish';
    const URL_UPLOAD_CHUNK_MAX_SIZE = 157286400; //150Mb


    use Upload;

    /**
     * Client constructor.
     * @param $accessToken string the access_token
     * @throws \Exception
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->guzzle = new \GuzzleHttp\Client();
    }
}
