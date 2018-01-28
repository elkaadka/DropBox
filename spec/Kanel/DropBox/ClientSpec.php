<?php

namespace spec\Kanel\DropBox;

use Kanel\DropBox\Client;
use Kanel\DropBox\Config;
use Kanel\DropBox\Exceptions\FileNotFoundException;
use PhpSpec\ObjectBehavior;
use const \Kanel\DropBox\FILE_EXISTS;
use const Kanel\DropBox\FILE_DOES_NOT_EXISTS;

class ClientSpec extends ObjectBehavior
{
    function it_should_be_able_to_construct_with_access_token()
    {
        $this->beConstructedWith('my_access_token');
    }

    function it_should_be_able_to_construct_with_config_object(Config $config)
    {
        $this->beConstructedWith($config);
    }

    function it_should_throw_exception_if_file_does_not_exist()
    {
        $this->beConstructedWith('my_access_token');

        $this->shouldThrow(FileNotFoundException::class)->during('upload', [FILE_DOES_NOT_EXISTS]);
    }
}
