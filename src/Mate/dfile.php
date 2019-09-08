<?php
use Illuminate\Support\Facades\Storage;

public static function dfile($name, $folder, $url)
    {
        $file['name']=$name;
        $file['original']=$url;
        $file['path']=$folder;
        $file['type']=strtok(pathinfo($url, PATHINFO_EXTENSION), '?');
        $file['size']=$size = Storage::size($folder.'/max/'.$name);
        return $file;
    }