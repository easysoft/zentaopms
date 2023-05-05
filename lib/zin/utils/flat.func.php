<?php
namespace zin\utils;

function flat($array, $prefix = '', $separator = '.')
{
    $result = array();
    foreach($array as $key => $value)
    {
        if(is_array($value))
        {
            $result = array_merge($result, flat($value, $prefix . $key . $separator));
        }
        else
        {
            $result[$prefix . $key] = $value;
        }
    }
    return $result;
}
