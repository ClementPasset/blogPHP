<?php
namespace App\Helpers;

class Text{

    public static function excerpt(string $content, int $limit=60):string
    {
        if(strlen($content)<=$limit){
            return $content;
        }
        return substr($content,0,strpos($content," ",$limit)) . "...";
    }

}