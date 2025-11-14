#!/usr/bin/env php
<?php

/**

title=测试 settingModel::getURSR();
timeout=0
cid=18363

- 执行settingTest模块的getURSRTest方法  @12345
- 执行settingTest模块的getURSRTest方法，参数是true  @2
- 执行settingTest模块的getURSRTest方法，参数是true  @0
- 执行settingTest模块的getURSRTest方法，参数是true  @0
- 执行settingTest模块的getURSRTest方法，参数是true  @12345

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/setting.unittest.class.php';

// 准备测试数据：创建包含URSR配置的config记录
$configTable = zenData('config');
$configTable->owner->range('system');
$configTable->module->range('custom');
$configTable->section->range('');
$configTable->key->range('URSR,other1,other2,other3,other4');
$configTable->value->range('2,value1,value2,value3,value4');
$configTable->gen(5);

su('admin');

$settingTest = new settingTest();

// 步骤1：测试从配置文件获取URSR（如果配置中存在URSR）
r($settingTest->getURSRTest()) && p() && e('12345');

// 步骤2：测试从数据库config表获取URSR（清空配置中的URSR）
r($settingTest->getURSRTest(true)) && p() && e('2');

// 步骤3：清空config中的URSR记录，测试不存在的情况
global $tester;
$tester->dao->delete()->from(TABLE_CONFIG)->where('`key`')->eq('URSR')->exec();
r($settingTest->getURSRTest(true)) && p() && e('0');

// 步骤4：重新插入URSR配置，值为空字符串
$tester->dao->insert(TABLE_CONFIG)->data(array('owner' => 'system', 'module' => 'custom', 'section' => '', 'key' => 'URSR', 'value' => ''))->exec();
r($settingTest->getURSRTest(true)) && p() && e('0');

// 步骤5：更新URSR配置为数字字符串
$tester->dao->update(TABLE_CONFIG)->set('value')->eq('12345')->where('`key`')->eq('URSR')->exec();
r($settingTest->getURSRTest(true)) && p() && e('12345');