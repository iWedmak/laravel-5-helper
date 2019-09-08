<?php
use Illuminate\Support\Facades\Storage;

public static function uname($folder, $original_name, $ext=false)
{
    if(!$ext)
    {
      $ext=dotfile($original_name);
    }
    
    $name=$original_name;
    $i=0;
    while(Storage::exists($folder.'/max/'.$name.'.'.$ext))
    {
        $name=$original_name.'_'.$i;
        $i++;
    }
    return $name;
}