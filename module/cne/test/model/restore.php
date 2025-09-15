#!/usr/bin/env php
<?php

/**

title=测试 cneModel::restore();
timeout=0
cid=0

- 步骤1：使用有效实例和备份名进行正常恢复属性code @200
- 步骤2：使用空备份名进行恢复属性message @~~
- 步骤3：使用特殊字符备份名进行恢复属性code @200
- 步骤4：使用不同用户账号进行恢复属性code @200
- 步骤5：验证恢复操作的参数传递正确性属性code @200

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
r($cneModel->restore($instance, 'backup-20231201-001', 'admin')) && p('code') && e('200');        // 步骤1：使用有效实例和备份名进行正常恢复
r($cneModel->restore($instance, '', 'admin')) && p('message') && e('~~');                         // 步骤2：使用空备份名进行恢复
r($cneModel->restore($instance, 'backup-#special@chars!', 'admin')) && p('code') && e('200');     // 步骤3：使用特殊字符备份名进行恢复
r($cneModel->restore($instance, 'backup-test-user', 'testuser')) && p('code') && e('200');        // 步骤4：使用不同用户账号进行恢复
r($cneModel->restore($instance, 'backup-consistency-test', '')) && p('code') && e('200');         // 步骤5：验证恢复操作的参数传递正确性