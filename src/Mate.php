<?php namespace iWedmak\Helper;

use Cache, Storage;
use Intervention\Image\Facades\Image as Image;
//use Illuminate\Filesystem\FileNotFoundException;

class Mate
{
    //need to move this in config file, so i can publish it
    public static $sizes =
        [
            'serial'=> 
                [
                    //'2304'=>'1152',
                    //'2160'=>'1132',
                    //'1920'=>'960',
                    //'1776'=>'888',
                    //'1248'=>'624',
                    //'1008'=>'1008',
                    '480'=>'480',
                    '240'=>'240',
                    '96'=>'96',
                    '60'=>'60'
                ],
            'people'=> [],
            'character'=> [],
            'user'=> [],
        ];
    
    public static function image_cook($folder, $url, $name=false)
    {
        $ext=Mate::url_extention($url);
        if(!$name)
        {
            $original_name=Mate::url_filename($url, true);
            $name=Mate::name_generate($folder, $original_name, $ext);
        }
        
        if(Mate::image_create($folder, $url, $name.'.'.$ext))
        {
            return Mate::file_data($name.'.'.$ext, $folder, $url);
        }
        return false;
    }
    
    public static function name_generate($folder, $original_name, $ext)
    {
        $name=$original_name;
        $i=0;
        while(Storage::exists($folder.'/max/'.$name.'.'.$ext))
        {
            $name=$original_name.'_'.$i;
            $i++;
        }
        return $name;
    }
    
    public static function file_data($name, $folder, $url)
    {
        $file['name']=$name;
        $file['original']=$url;
        $file['path']=$folder;
        $file['type']=strtok(pathinfo($url, PATHINFO_EXTENSION), '?');
        $file['size']=$size = Storage::size($folder.'/max/'.$name);
        return $file;
    }
    
    public static function image_create($folder, $url, $name)
    {
        if(fopen($url, "r"))
        {
            $image=Image::make(file_get_contents($url));
            //$image->save(public_path(), 100);
            Storage::put($folder.'/max/'.$name, $image->stream()->__toString());
            try
            {
                $lastModified = Storage::lastModified($folder.'/max/'.$name);
            }
            catch(\League\Flysystem\FileNotFoundException $e)
            {
                $lastModified=false;
            }
            foreach(Mate::$sizes[$folder] as $w=>$h)
            {
                try
                {
                    $lastModified2 = Storage::lastModified($folder.'/'.$w.'/'.$name);
                }
                catch(\League\Flysystem\FileNotFoundException $e)
                {
                    $lastModified2=false;
                }
                if( ($lastModified==false) || ($lastModified2==false) || ($lastModified > $lastModified2))
                {
                    $image->fit((int)$w, (int)$h);
                    Storage::put($folder.'/'.$w.'/'.$name, $image->stream()->__toString());
                    //$image->save(public_path($folder.'/'.$w.'/'.$name), 100);
                }
            }
            return true;
        }
        return false;
    }
    
    public static function url_extention($str)
    {
        //pre($str);
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
            return Mate::file_extention($str);
        }

        // HACKISH CATCH ALL (WHICH IN MY CASE IS
        // PREFERRED OVER THROWING AN EXCEPTION)
        
    }
    
    public static function file_extention($str)
    {
        $ext='jpg';
        $parse=parse_url($str);
        $ex=explode('.',$parse['path']);
        $c=count($ex);
        if($c>0)
        {
            $ext=end($ex);
        }
        $ext=strtok($ext, '?');
        return $ext;
    }
    
    public static function url_filename($str, $name_only=false)
    {
        $name_a=explode('/', $str);
        $c=count($name_a);
        if($c>0)
        {
            if($name_only)
            {
                $name=str_replace('.'.Mate::file_extention($name_a[$c-1]), '', strtok($name_a[$c-1], '?'));
            }
            else
            {
                $name=$name_a[$c-1];
            }
        }
        return $name;
    }
    
    public static function file_array_filter(&$array, $ext)
    {
        $array = array_where($array, function ($key, $value) use($ext) 
        {
            return (Mate::url_extention($value)==$ext);
        });
        array_multisort($array, SORT_ASC);
    }
    
    public static function clean_drupal_value($value, $defualt=false)
    {
        if(isset($value['und'][0]) && !empty($value['und'][0]))
        {
            if(isset($value['und'][0]['value']) && (!empty($value['und'][0]['value']) || $value['und'][0]['value']==0) )
            {
                return $value['und'][0]['value'];
            }
            elseif(isset($value['und'][0]['amount']) && (!empty($value['und'][0]['amount']) || $value['und'][0]['amount']==0))
            {
                return $value['und'][0]['amount'];
            }
            return $defualt;
        }
        else
        {
            return $defualt;
        }
    }
    
    public static function clat($in)
    {
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", "ё"=>"e", 
            " "=> "-", "."=> "", "/"=> "_", ","=> "", "'"=> "", 
            '"'=> "", '['=> "", ']'=> "", '{'=> "", '}'=> "", '|'=> "", 
			'&'=> "", '?'=> "", ':'=> "", '%'=> "", '<'=> "",'>'=> "",
			'«'=> "",'»'=> "",'*'=> "",'('=> "",')'=> "",'@'=> "",
			'!'=> "",'#'=> "",'$'=> "",'^'=> "",'\''=> "",'\''=> "",
			'№'=> "",';'=> "",'\\'=> "",','=> "",'~'=> "",'–'=> "",
			'№'=> "",';'=> "",'\\'=> "",','=> "",'~'=> "",'–'=> "",'”'=> "",'“'=> "",'—'=> "",
        );
        $str=strtolower(strtr(trim($in),$tr));
        $str=preg_replace('/[^A-Za-z0-9\-]/', '', $str);
        return $str;
        //return $out;
    }
    
    public static function search($in)
    {
        $tr = array(
            '-'=>" +",
            ' '=>" +",
            //" "=>"*+",
            "'"=>"",
            '"'=>"",
            '/'=>"",
            '\\'=>"",
            ';'=>"",
            '#'=>"",
            '$'=>"", '@'=>"", '%'=>"", '&'=>"", '*'=>"", '('=>"", ')'=>"",
        );
        
        return strtolower(strtr($in,$tr));
        //return $out;
    }
    
    public static function sizer($size)
    {
        preg_match('/(?<value>([0-9]*[.])?[0-9]+)\s+?(?<measure>\w)/iu', $size, $match);
        if(isset($match['measure']))
        {
            switch (mb_strtoupper($match['measure'])) 
            {
                case 'B':
                    $newSize = $match['value'] ;
                    break;
                case 'K':
                    $newSize = $match['value'] * 1024;
                    break;
                case 'M':
                    $newSize = $match['value'] * pow(1024, 2);
                    break;
                case 'G':
                    $newSize = $match['value'] * pow(1024, 3);
                    break;
                case 'T':
                    $newSize = $match['value'] * pow(1024, 4);
                    break;
                case 'P':
                    $newSize = $match['value'] * pow(1024, 5);
                    break;
                case 'E':
                    $newSize = $match['value'] * pow(1024, 6);
                    break;
                case 'Z':
                    $newSize = $match['value'] * pow(1024, 7);
                    break;
                case 'Y':
                    $newSize = $match['value'] * pow(1024, 8);
                    break;
                default:
                    return $match['value'];
            }
            return $newSize;
        }
        return $size;
    } 
    
    public static function size($size)
    {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    } 
    
    public static function match($pattern, $text, $force = true)
    {
        $data = explode('[*]', $pattern);

        if (count($data) >= 2 && (count($data) % 2 == 0)) 
        {
          
            for($i = 1; $i <= count($data) / 2; $i++) 
            {
                $text = Mate::find($data[$i - 1], $data[count($data) - $i], $text);
            }
          
            return $text;
        }

        return false;
    }

    public static function find($start, $end, $text, $force = true)
    {
        $text = str_ireplace(array(
          "\t", "\r", "\n", PHP_EOL 
        ), '', $text);
        
        $temp = strstr($text, $start);
        if ($temp) 
        {
            $endPos = stripos($temp, $end);
            if ($endPos) 
            {
                $return = substr($temp, 0, $endPos);
                if ($return) 
                {
                  
                    if ($force) 
                    {
                        $return = str_ireplace(array(
                            $start, $end 
                        ), '', $return);
                    }
                    return $return;
                }
            }
        }
        
        return false;
    }
    
    public static function array_intermerge($arr=[], $to_merge=[])
    {
        if($arr && $to_merge)
        {
            return array_intersect($arr, $to_merge);
        }
        else
        {
            return $arr ?? $to_merge;
        }
    }
}