<?php

namespace Elecms\ElecmsBundle\Utils;

class Helper
{
    // Just a text cleaning function which removes "Object:(..." from beginning
    // of the string and "(code X)" from the string ending
    protected static function ValidationCleanString($string)
    {
        $pattern = '/^Object.*:/';
        $preg = preg_replace($pattern,'',$string);

        if(strpos($preg,"(code")) {
           $pattern = '/^Object.*:/';
           $preg = preg_replace($pattern,'',$string);

           $pattern2 = '/\(code.*/';
           $preg2 = preg_replace($pattern2,'',$preg);

            return '- '.$preg2;
        }

        return '- '.$preg;
    }


    public static function RenderErrors($error)
    {
        $errorsArray = array();

        if (count($error) > 0) {
            foreach($error as $err)
                $errorsArray[] = self::ValidationCleanString((string) $err);

            return implode("<br>", $errorsArray);
        }

        return '';
    }
}