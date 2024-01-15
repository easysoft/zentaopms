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

    include_once dirname(__DIR__) . DS . 'wg' . DS . $name . DS . "v$wgVer.php";

    $wgName = "\\zin\\$name";

    return class_exists($wgName) ? (new $wgName($args)) : $wgName($args);
}

function requireWg(string $name, string $wgVer = '')
{
    $name = strtolower($name);

    if(!$wgVer) $wgVer = getWgVer($name);

    require_once dirname(__DIR__) . DS . 'wg' . DS . $name . DS . "v$wgVer.php";
}
