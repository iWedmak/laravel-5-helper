<?php

public static function doturl($str)
{
    $type=get_headers($str, 1)["Content-Type"];
    $map = array(
        'application/pdf'   => 'pdf',
        'application/zip'   => 'zip',
        'image/gif'         => 'gif',
        'image/jpeg'        => 'jpg',
        'image/png'         => 'png',
        'text/css'          => 'css',
        'text/html'         => 'html',
        'text/javascript'   => 'js',
        'text/plain'        => 'txt',
        'text/xml'          => 'xml',
    );
    if (isset($map[$type]))
    {
        return $map[$type];
    }
    else
    {
        return dotfile($str);
    }
    // HACKISH CATCH ALL (WHICH IN MY CASE IS
    // PREFERRED OVER THROWING AN EXCEPTION)
}