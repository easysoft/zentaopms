<?php
declare(strict_types=1);
namespace zin\utils;

function deepGet(object|array &$data, string $namePath, mixed $defaultValue = null): mixed
{
    $names = explode('.', $namePath);
    foreach($names as $name)
    {
        if(is_object($data))
        {
            if(!isset($data->$name)) return $defaultValue;
            $data = &$data->$name;
            continue;
        }
        if(!is_array($data) || !isset($data[$name])) return $defaultValue;
        $data = &$data[$name];
    }
    return $data === null ? $defaultValue : $data;
}

function deepSet(array &$data, string $namePath, mixed $value)
{
    $names = explode('.', $namePath);
    $lastName = array_pop($names);
    if(!empty($names))
    {
        foreach($names as $name)
        {
            if(!is_array($data)) return;

            if(!isset($data[$name])) $data[$name] = array();
            $data = &$data[$name];
        }
    }

    $data[$lastName] = $value;
}
