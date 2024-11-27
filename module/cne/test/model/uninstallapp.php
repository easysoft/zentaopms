#!/usr/bin/env php
<?php

/**

title=测试 cneModel->uninstallApp();
timeout=0
cid=1

- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
- 卸载CNE平台的卸载应用 code @0
 */

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://354z.corp.cc:32380';
$config->CNE->api->token  = 'LuWsdueJ2GqnE6agDG5YMK5YB7kWIWs4';
$config->CNE->app->domain = '354z.corp.cc';

$cneModel = $tester->loadModel('cne');

$instance = $cneModel->loadModel('instance')->getByID(2);

$apiParams = new stdclass();
$apiParams->cluster   = '';
$apiParams->name      = $instance->k8name ?? '';
$apiParams->chart     = $instance->chart ?? '';
$apiParams->namespace = $instance->spaceData->k8space ?? '';
$apiParams->channel   = $instance->channel ?? 'test';

r($cneModel->uninstallApp($apiParams)) && p('code') && e('600');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('600');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('600');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('600');
r($cneModel->uninstallApp($apiParams)) && p('code') && e('600');