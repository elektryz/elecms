<?php

namespace Elecms\ElecmsBundle\Utils;

class Helper
{
    // Just a text cleaning function which removes "Object:(..." from beginning
    // of the string and "(code X)" from the string ending
    public static function ValidationCleanString($string)
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
}