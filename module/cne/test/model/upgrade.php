#!/usr/bin/env php
<?php

/**

title=测试 cneModel->upgrade();
timeout=0
cid=1

- 升级禅道DevOps平台版 status @0
- 升级禅道DevOps平台版 status @0
- 升级禅道DevOps平台版 status @0
- 升级禅道DevOps平台版 status @0
- 升级禅道DevOps平台版 status @0

 */

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://g79n.corp.cc:32380';
$config->CNE->api->token  = 'hYFfFOTUR5CIBoonLFx1UjnmQ7NtBxo9';
$config->CNE->app->domain = 'g79n.corp.cc';

$cneModel = $tester->loadModel('cne');

r($cneModel->upgrade()) && p('status') && e('0');
r($cneModel->upgrade()) && p('status') && e('0');
r($cneModel->upgrade()) && p('status') && e('0');
r($cneModel->upgrade()) && p('status') && e('0');
r($cneModel->upgrade()) && p('status') && e('0');
