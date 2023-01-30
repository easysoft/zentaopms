<?php
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

require_once 'config.php';

function setWgVer($ver, $names = NULL)
{
    global $config;
    $zinConfig = $config->zin;

    a($zinConfig);

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

function createWg($name, $args)
{
    global $app;

    $wgVer = getWgVer($name);

    include_once $app->getBasePath() . 'zin' . DS . 'wg' . DS . $name . DS . "v$wgVer.php";

    $className = "\\zin\\wg\\$name";

    return class_exists($className) ? (new $className($args)) : $className($args);
}
