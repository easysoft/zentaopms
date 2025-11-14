#!/usr/bin/env php
<?php

/**

title=测试 cneModel->cneMetrics();
timeout=0
cid=15607

- 获取CNE平台的集群度量状态属性status @unknown
- 获取CNE平台的集群cpu数据属性usage @0
- 获取CNE平台的集群cpu数据属性capacity @0
- 获取CNE平台的集群内存属性usage @0
- 获取CNE平台的集群内存属性capacity @0
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://dev.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'dev.corp.cc';

$cneModel = $tester->loadModel('cne');

r($cneModel->cneMetrics())                  && p('status')   && e('unknown'); // 获取CNE平台的集群度量状态
r($cneModel->cneMetrics()->metrics->cpu)    && p('usage')    && e('0'); // 获取CNE平台的集群cpu数据
r($cneModel->cneMetrics()->metrics->cpu)    && p('capacity') && e('0'); // 获取CNE平台的集群cpu数据
r($cneModel->cneMetrics()->metrics->memory) && p('usage')    && e('0'); // 获取CNE平台的集群内存
r($cneModel->cneMetrics()->metrics->memory) && p('capacity') && e('0'); // 获取CNE平台的集群内存
