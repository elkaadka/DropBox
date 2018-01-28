<?php

namespace Kanel\DropBox;

use Kanel\DropBox\EndPoints\Get;
use Kanel\DropBox\EndPoints\GetTemporaryLink;
use Kanel\DropBox\EndPoints\Upload;

class Client
{
    protected $accessToken;
    protected $guzzle;

    //File Get end points
    const URL_FILE_REQUEST_GET = 'https://api.dropboxapi.com/2/file_requests/get';

    //get temporary link
    const URL_GET_TEMPORARY_LINK = 'https://api.dropboxapi.com/2/files/get_temporary_link';

    //Upload url end points
    const URL_FILE_UPLOAD = 'https://content.dropboxapi.com/2/files/upload';
    const URL_UPLOAD_START_SESSION = 'https://content.dropboxapi.com/2/files/upload_session/start';
    const URL_UPLOAD_APPEND = 'https://content.dropboxapi.com/2/files/upload_session/append_v2';
    const URL_UPLOAD_FINISH_SESSION = 'https://content.dropboxapi.com/2/files/upload_session/finish';
    const URL_UPLOAD_CHUNK_MAX_SIZE = 157286400; //150Mb


    use Get;
    use Upload;
    use GetTemporaryLink;

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
