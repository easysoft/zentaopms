<?php
global $app, $config;

$config->zin = new stdClass();

$config->zin->wgVer    = isset($config->wgVer) ? $config->wgVer : '1';
$config->zin->wgVerMap = isset($config->wgVerMap) ? $config->wgVerMap : array();
$config->zin->zuiPath  = isset($config->zuiPath) ? $config->zuiPath : ($app->getWebRoot() . 'js/zui3/');
