<?php
declare(strict_types=1);
namespace zin\utils;

/**
 * 对数据进行HTML实体转为字符。
 * Un HTML array.
 *
 * @param  mixed  $data
 * @access public
 * @return mixed
 */
function unHTMLArray($data, $processed = array())
{
    if(is_string($data)) return htmlspecialchars_decode($data);

    if(is_array($data) || is_object($data))
    {
        if(in_array($data, $processed, true)) return $data;

        $processed[] = $data;
        foreach($data as &$value) $value = unHTMLArray($value, $processed);
    }
    return $data;
}


function jsonEncode($data, $flags = 0, $depth = 512): string|false
{
    $data = unHTMLArray($data);
    return json_encode($data, $flags, $depth);
}
