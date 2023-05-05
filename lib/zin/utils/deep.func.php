<?php
namespace zin\utils;

function deepGet(&$data, $namePath, $defaultValue = NULL)
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
    return $data === NULL ? $defaultValue : $data;
}

function deepSet(&$data, $namePath, $value)
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
