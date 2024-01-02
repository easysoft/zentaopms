#!/usr/bin/env php
<?php

/**

title=测试 cneModel->cneMetrics();
timeout=0
cid=1

- 获取CNE平台的集群度量属性status @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://10.0.7.210:32380';
$config->CNE->api->token  = 'JMz7HCoQ3WHoYbpNyYNpvMfHqde9ugtV';
$config->CNE->app->domain = 'dops.corp.cc';

$cneModel = $tester->loadModel('cne');

r($cneModel->cneMetrics()) && p('status') && e('normal'); // 获取CNE平台的集群度量