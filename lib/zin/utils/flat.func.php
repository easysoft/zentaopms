<?php
declare(strict_types=1);
namespace zin\utils;

function flat(array $array, string $prefix = '')
{
    $result = array();
    foreach($array as $key => $value)
    {
        if(is_array($value))
        {
            $result = array_merge($result, flat($value, "{$prefix}{$key}."));
        }
        else
        {
            $result[$prefix . $key] = $value;
        }
    }
    return $result;
}
