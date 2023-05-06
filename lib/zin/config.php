<?php
/**
 * The config file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

global $app, $config;

$config->zin = new \stdClass();

$config->zin->lang     = $app->getClientLang();
$config->zin->wgVer    = isset($config->wgVer) ? $config->wgVer : '1';
$config->zin->wgVerMap = isset($config->wgVerMap) ? $config->wgVerMap : array();
$config->zin->zuiPath  = isset($config->zuiPath) ? $config->zuiPath : ($app->getWebRoot() . 'js/zui3/');
