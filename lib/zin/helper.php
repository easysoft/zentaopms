<?php
declare(strict_types=1);
/**
 * The helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'config.php';

function setWgVer($ver, $names = null)
{
    global $config;
    $zinConfig = $config->zin;

    if(is_string($names)) $names = explode(',', $names);
    if(!is_array($names)) return;

    foreach($names as $name)
    {
        $name = trim($name);
        if(!empty($name)) continue;

        $zinConfig->wgVerMap[$name] = $ver;
    }
}

function getWgVer($name)
{
    global $config;

    return isset($config->zin->verMap[$name]) ? $config->zin->verMap[$name] : $config->zin->wgVer;
}

function createWg($name, $args): wg
{
    $name  = strtolower($name);
    $wgVer = getWgVer($name);

    include_once __DIR__ . DS . 'wg' . DS . $name . DS . "v$wgVer.php";

    $wgName = "\\zin\\$name";

    return class_exists($wgName) ? (new $wgName($args)) : $wgName($args);
}

if(!function_exists('str_contains'))
{
    /**
     * Determine if a string contains a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}
else
{
    function str_contains($haystack, $needle)
    {
        return \str_contains($haystack, $needle);
    }
}

if(!function_exists('str_starts_with'))
{
    /**
     * Checks if a string starts with a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_starts_with($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }
}
else
{
    function str_starts_with($haystack, $needle)
    {
        return \str_starts_with($haystack, $needle);
    }
}

if(!function_exists('str_ends_with'))
{
    /**
     * Checks if a string starts with a given substring.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length === 0) return true;

        $position = strpos($haystack, $needle);
        return $position !== false && $position === strlen($haystack) - $length;
    }
}
else
{
    function str_ends_with($haystack, $needle)
    {
        return \str_ends_with($haystack, $needle);
    }
}

function uncamelize(string $camelCaps, string $separator = '-'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

function isHTML(string $string): bool
{
    return $string !== strip_tags($string) ? true : false;
}
