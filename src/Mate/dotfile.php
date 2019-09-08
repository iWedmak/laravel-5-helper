<?php

public static function dotfile($str)
{
    $ext='jpg';
    $parse=parse_url($str);
    $ex=explode('.',$parse['path']);
    if(count($ex)>0)
    {
        $ext=end($ex);
    }
    $ext=strtok($ext, '?');
    return $ext;
}