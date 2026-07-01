#!/usr/bin/env php
<?php

/**

title=测试 systemModel::getDomainSettings();
timeout=0
cid=18734

- 执行systemTest模块的getDomainSettingsTest方法 属性https @true
- 执行systemTest模块的getDomainSettingsTest方法 属性customDomain @example.domain.com
- 执行systemTest模块的getDomainSettingsTest方法 属性certPem @~~
- 执行systemTest模块的getDomainSettingsTest方法 属性certKey @~~
- 执行systemTest模块的getDomainSettingsTest方法 属性https @false
- 执行systemTest模块的getDomainSettingsTest方法 属性customDomain @example.domain.com
- 执行systemTest模块的getDomainSettingsTest方法 属性certPem @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');
global $tester;
$dao = $tester->dao;
$systemTest = new systemModelTest();

$dao->delete()->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('section')->eq('domain')->exec();

// 测试步骤1：正常配置情况下验证https属性
$dao->insert(TABLE_CONFIG)->data(array('id' => 1001, 'owner' => 'system', 'module' => 'common', 'section' => 'domain', 'key' => 'https', 'value' => 'true'))->exec();
$dao->insert(TABLE_CONFIG)->data(array('id' => 1002, 'owner' => 'system', 'module' => 'common', 'section' => 'domain', 'key' => 'customDomain', 'value' => 'example.domain.com'))->exec();

r($systemTest->getDomainSettingsTest()) && p('https') && e('true');

// 测试步骤2：正常配置情况下验证customDomain属性
r($systemTest->getDomainSettingsTest()) && p('customDomain') && e('example.domain.com');

// 测试步骤3：验证certPem固定属性始终为空
r($systemTest->getDomainSettingsTest()) && p('certPem') && e('~~');

// 测试步骤4：验证certKey固定属性始终为空
r($systemTest->getDomainSettingsTest()) && p('certKey') && e('~~');

// 测试步骤5：配置变更后验证https属性反映最新配置
$dao->update(TABLE_CONFIG)->set('value')->eq('false')->where('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('section')->eq('domain')->andWhere('key')->eq('https')->exec();

r($systemTest->getDomainSettingsTest()) && p('https') && e('false');

// 测试步骤6：配置变更后验证customDomain属性持久性
r($systemTest->getDomainSettingsTest()) && p('customDomain') && e('example.domain.com');

// 测试步骤7：配置清空后验证certPem属性稳定性
$dao->delete()->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('common')->andWhere('section')->eq('domain')->exec();

r($systemTest->getDomainSettingsTest()) && p('certPem') && e('~~');
