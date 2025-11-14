#!/usr/bin/env php
<?php

/**

title=测试 cneModel->uninstallApp();
timeout=0
cid=15633

- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
 */

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://devops.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'devops.corp.cc';

$cneModel = $tester->loadModel('cne');

$instance = $cneModel->loadModel('instance')->getByID(2);

$apiParams = new stdclass();
$apiParams->cluster   = '';
$apiParams->name      = $instance->k8name ?? '';
$apiParams->chart     = $instance->chart ?? '';
$apiParams->namespace = $instance->spaceData->k8space ?? '';
$apiParams->channel   = $instance->channel ?? '';

r($cneModel->uninstallApp($apiParams)) && p('code') && e('0');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('0');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('0');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('0');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('0');
