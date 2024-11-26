#!/usr/bin/env php
<?php

/**

title=测试 cneModel->uninstallApp();
timeout=0
cid=1

- 卸载CNE平台的卸载应用 status @normal
- 卸载CNE平台的卸载应用 status @normal
- 卸载CNE平台的卸载应用 status @normal
- 卸载CNE平台的卸载应用 status @normal
- 卸载CNE平台的卸载应用 status @normal
 */

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://g79n.corp.cc:32380';
$config->CNE->api->token  = 'hYFfFOTUR5CIBoonLFx1UjnmQ7NtBxo9';
$config->CNE->app->domain = 'g79n.corp.cc';

$instance = $this->objectModel->loadModel('instance')->getByID(2);

$apiParams = new stdclass();
$apiParams->cluster   = '';
$apiParams->name      = $instance->k8name;
$apiParams->chart     = $instance->chart;
$apiParams->namespace = $instance->spaceData->k8space;
$apiParams->channel   = $instance->channel;

$cneModel = $tester->loadModel('cne');

r($cneModel->uninstallApp($apiParams)) && p('status') && e('normal');
r($cneModel->uninstallApp($apiParams)) && p('status') && e('normal');
r($cneModel->uninstallApp($apiParams)) && p('status') && e('normal');
r($cneModel->uninstallApp($apiParams)) && p('status') && e('normal');
r($cneModel->uninstallApp($apiParams)) && p('status') && e('normal');