<?php

namespace PauloAK\NfseLajeado\Helpers;

class Utils {

    public static function onlyNumbers($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

}