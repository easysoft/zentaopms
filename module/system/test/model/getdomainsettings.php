#!/usr/bin/env php
<?php

/**

title=测试 systemModel::getDomainSettings();
timeout=0
cid=18734

- 执行systemTest模块的getDomainSettingsTest方法 属性https @true
- 执行systemTest模块的getDomainSettingsTest方法 属性customDomain @example.domain.com
- 执行systemTest模块的getDomainSettingsTest方法 属性certPem @
- 执行systemTest模块的getDomainSettingsTest方法 属性certKey @
- 执行systemTest模块的getDomainSettingsTest方法 属性https @true
- 执行systemTest模块的getDomainSettingsTest方法 属性customDomain @example.domain.com
- 执行systemTest模块的getDomainSettingsTest方法 属性certPem @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');
$systemTest = new systemModelTest();

// 测试步骤1：正常配置情况下验证https属性
$configData = zenData('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('https,customDomain');
$configData->value->range('true,example.domain.com');
$configData->gen(2);

r($systemTest->getDomainSettingsTest()) && p('https') && e('true');

// 测试步骤2：正常配置情况下验证customDomain属性
r($systemTest->getDomainSettingsTest()) && p('customDomain') && e('example.domain.com');

// 测试步骤3：验证certPem固定属性始终为空
r($systemTest->getDomainSettingsTest()) && p('certPem') && e('');

// 测试步骤4：验证certKey固定属性始终为空
r($systemTest->getDomainSettingsTest()) && p('certKey') && e('');

// 测试步骤5：配置变更后验证https属性持久性
$configData = zenData('config');
$configData->owner->range('system');
$configData->module->range('common');
$configData->section->range('domain');
$configData->key->range('https');
$configData->value->range('false');
$configData->gen(1);

r($systemTest->getDomainSettingsTest()) && p('https') && e('true');

// 测试步骤6：配置变更后验证customDomain属性持久性
r($systemTest->getDomainSettingsTest()) && p('customDomain') && e('example.domain.com');

// 测试步骤7：配置清空后验证certPem属性稳定性
$configData = zenData('config');
$configData->gen(0);

r($systemTest->getDomainSettingsTest()) && p('certPem') && e('');