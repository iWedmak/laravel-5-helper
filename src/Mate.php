<?php namespace iWedmak\Helper;

class Mate
{
    
    public static function url_extention($str)
    {
        $ext='jpg';
        $parse=parse_url($str);
        $ex=explode('.',$parse['path']);
        $c=count($ex);
        if($c>0)
        {
            $ext=end($ex);
        }
        return $ext;
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
    
}