<?php

namespace Kanel\DropBox
{
    const FILE_EXISTS = 'file1';
    const FILE_DOES_NOT_EXISTS = 'file2';

    function file_exists($file)
    {
        return $file === FILE_EXISTS ? true : false;
    }
}