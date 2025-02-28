<?php
include '../test/result.php';
$links = array();
foreach($link as $module => $group)
{
    foreach($group as $name => $url)
    {
        $method = explode('_', $name)[0];
        $links[$module][$method][$name] = $url;
    }
}
