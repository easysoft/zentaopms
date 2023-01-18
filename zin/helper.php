<?php
require_once 'config.php';

function useWgVer($ver, $names = NULL)
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

    return class_exists($name) ? (new $name($args)) : $name($args);
}
