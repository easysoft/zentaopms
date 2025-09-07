#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getSettingsMapping();
timeout=0
cid=0

- 执行$result1 @type1
- 执行$result2 @type2
- 执行$result3 @type3
- 执行$result4 @type4
- 执行$result5 @type5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester, $config;
$config->CNE->api->host   = 'http://devops.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'devops.corp.cc';
$config->CNE->api->channel = 'stable';

$cneModel = $tester->loadModel('cne');

// 创建模拟instance对象用于测试
$instance = new stdclass();
$instance->id = 1;
$instance->k8name = 'test-zentao-app';
$instance->chart = 'zentao';
$instance->spaceData = new stdclass();
$instance->spaceData->k8space = 'test-namespace';
$instance->channel = 'stable';

// 测试1：使用默认mappings
$result1 = $cneModel->getSettingsMapping($instance);
$type1 = is_object($result1) ? 'object' : (is_null($result1) ? 'NULL' : gettype($result1));

// 测试2：使用自定义mappings数组
$customMappings = array(
    array('key' => 'custom_username', 'type' => 'helm', 'path' => 'auth.custom_username'),
    array('key' => 'custom_password', 'type' => 'secret', 'path' => 'custom_password')
);
$result2 = $cneModel->getSettingsMapping($instance, $customMappings);
$type2 = is_object($result2) ? 'object' : (is_null($result2) ? 'NULL' : gettype($result2));

// 测试3：使用空mappings数组（应该使用默认配置）
$result3 = $cneModel->getSettingsMapping($instance, array());
$type3 = is_object($result3) ? 'object' : (is_null($result3) ? 'NULL' : gettype($result3));

// 测试4：使用多个mapping配置
$multipleMappings = array(
    array('key' => 'db_username', 'type' => 'secret', 'path' => 'database.username'),
    array('key' => 'db_password', 'type' => 'secret', 'path' => 'database.password'),
    array('key' => 'admin_email', 'type' => 'helm', 'path' => 'admin.email')
);
$result4 = $cneModel->getSettingsMapping($instance, $multipleMappings);
$type4 = is_object($result4) ? 'object' : (is_null($result4) ? 'NULL' : gettype($result4));

// 测试5：模拟API错误响应（使用无效的instance）
$invalidInstance = new stdclass();
$invalidInstance->spaceData = new stdclass();
$invalidInstance->spaceData->k8space = 'invalid-namespace';
$invalidInstance->k8name = 'invalid-app';
$result5 = $cneModel->getSettingsMapping($invalidInstance);
$type5 = is_object($result5) ? 'object' : (is_null($result5) ? 'NULL' : gettype($result5));

// 输出测试结果
r($result1) && p() && e($type1);
r($result2) && p() && e($type2);
r($result3) && p() && e($type3);
r($result4) && p() && e($type4);
r($result5) && p() && e($type5);