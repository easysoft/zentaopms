#!/usr/bin/env php
<?php

/**

title=测试 cneModel::appDBDetail();
timeout=0
cid=0

- 步骤1：正常情况但无外部API连接 @false
- 步骤2：无效实例 @false
- 步骤3：空实例对象 @false
- 步骤4：空k8name @false
- 步骤5：空spaceData @false

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';

// 2. 全局配置设置
global $tester, $config;
$config->CNE->api->host   = 'http://dev.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'dev.corp.cc';

// 3. 创建模拟实例对象用于测试
$instance1 = new stdClass();
$instance1->k8name = 'test-zentao-app';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'test-namespace';

$instance2 = new stdClass();
$instance2->k8name = 'invalid-app';
$instance2->spaceData = new stdClass();
$instance2->spaceData->k8space = 'invalid-namespace';

$instance3 = null; // 空实例对象

$instance4 = new stdClass();
$instance4->k8name = ''; // 空k8name
$instance4->spaceData = new stdClass();
$instance4->spaceData->k8space = 'test-namespace';

$instance5 = new stdClass();
$instance5->k8name = 'test-app';
$instance5->spaceData = null; // 空spaceData

// 4. 加载cne模型
$cneModel = $tester->loadModel('cne');

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneModel->appDBDetail($instance1, 'zentao')) && p() && e('false'); // 步骤1：正常情况但无外部API连接
r($cneModel->appDBDetail($instance2, 'test_db')) && p() && e('false'); // 步骤2：无效实例
r($cneModel->appDBDetail($instance3, 'zentao')) && p() && e('false'); // 步骤3：空实例对象
r($cneModel->appDBDetail($instance4, 'zentao')) && p() && e('false'); // 步骤4：空k8name
r($cneModel->appDBDetail($instance5, 'zentao')) && p() && e('false'); // 步骤5：空spaceData