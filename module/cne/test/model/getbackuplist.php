#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupList();
timeout=0
cid=0

- 步骤1：正常实例获取备份列表属性code @200
- 步骤2：正常实例获取空备份数据属性data @~~
- 步骤3：正常调用无错误信息属性message @~~
- 步骤4：重复调用返回一致代码属性code @200
- 步骤5：第五次调用验证稳定性属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://dev.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'dev.corp.cc';

$cneModel = $tester->loadModel('cne');

// Mock instance for testing
$instance = new stdclass();
$instance->k8name = 'test-instance';
$instance->spaceData = new stdclass();
$instance->spaceData->k8space = 'test-namespace';
$instance->channel = 'stable';

// 直接测试方法，不依赖数据库
r($cneModel->getBackupList($instance))           && p('code') && e('200');    // 步骤1：正常实例获取备份列表
r($cneModel->getBackupList($instance))           && p('data') && e('~~');     // 步骤2：正常实例获取空备份数据
r($cneModel->getBackupList($instance))           && p('message') && e('~~'); // 步骤3：正常调用无错误信息
r($cneModel->getBackupList($instance))           && p('code') && e('200');    // 步骤4：重复调用返回一致代码
r($cneModel->getBackupList($instance))           && p('code') && e('200');    // 步骤5：第五次调用验证稳定性