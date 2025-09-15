#!/usr/bin/env php
<?php

/**

title=测试 cneModel::deleteBackup();
timeout=0
cid=0

- 步骤1：正常删除已存在的备份属性code @200
- 步骤2：删除不存在的备份属性code @200
- 步骤3：使用空备份名称删除属性message @~~
- 步骤4：删除包含特殊字符的备份名称属性code @200
- 步骤5：验证删除操作的一致性属性code @200

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
r($cneModel->deleteBackup($instance, 'backup-20231201-001')) && p('code') && e('200');      // 步骤1：正常删除已存在的备份
r($cneModel->deleteBackup($instance, 'nonexistent-backup')) && p('code') && e('200');       // 步骤2：删除不存在的备份
r($cneModel->deleteBackup($instance, '')) && p('message') && e('~~');                       // 步骤3：使用空备份名称删除
r($cneModel->deleteBackup($instance, 'backup-#special@chars!')) && p('code') && e('200');   // 步骤4：删除包含特殊字符的备份名称
r($cneModel->deleteBackup($instance, 'backup-consistency-test')) && p('code') && e('200');  // 步骤5：验证删除操作的一致性