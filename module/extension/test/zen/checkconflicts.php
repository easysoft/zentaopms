#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkConflicts();
timeout=0
cid=0

- 步骤1：无冲突插件情况 @1
- 步骤2：存在冲突插件但版本不匹配 @1
- 步骤3：存在冲突插件且版本匹配 @0
- 步骤4：多个冲突插件但都不匹配 @1
- 步骤5：多个冲突插件且其中一个匹配 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/extension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('extension');
$table->id->range('1-10');
$table->name->range('插件1,插件2,冲突插件A,冲突插件B,普通插件1,普通插件2,普通插件3,普通插件4,普通插件5,普通插件6');
$table->code->range('plugin1,plugin2,conflict_a,conflict_b,normal1,normal2,normal3,normal4,normal5,normal6');
$table->version->range('1.0,2.0,1.5,2.5,1.0,1.1,1.2,1.3,1.4,1.5');
$table->status->range('installed{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$extensionTest = new extensionTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($extensionTest->checkConflictsTest((object)array('conflicts' => null), array())) && p() && e('1'); // 步骤1：无冲突插件情况
r($extensionTest->checkConflictsTest((object)array('conflicts' => array('nonexist' => array('min' => '1.0', 'max' => '2.0'))), array('plugin1' => (object)array('code' => 'plugin1', 'name' => '插件1', 'version' => '1.0')))) && p() && e('1'); // 步骤2：存在冲突插件但版本不匹配
r($extensionTest->checkConflictsTest((object)array('conflicts' => array('plugin1' => array('min' => '1.0', 'max' => '2.0'))), array('plugin1' => (object)array('code' => 'plugin1', 'name' => '插件1', 'version' => '1.5')))) && p() && e('0'); // 步骤3：存在冲突插件且版本匹配
r($extensionTest->checkConflictsTest((object)array('conflicts' => array('nonexist1' => array('min' => '1.0'), 'nonexist2' => array('max' => '2.0'))), array('plugin1' => (object)array('code' => 'plugin1', 'name' => '插件1', 'version' => '1.0')))) && p() && e('1'); // 步骤4：多个冲突插件但都不匹配
r($extensionTest->checkConflictsTest((object)array('conflicts' => array('plugin1' => array('min' => '1.0'), 'plugin2' => array('min' => '3.0'))), array('plugin1' => (object)array('code' => 'plugin1', 'name' => '插件1', 'version' => '1.5'), 'plugin2' => (object)array('code' => 'plugin2', 'name' => '插件2', 'version' => '2.0')))) && p() && e('0'); // 步骤5：多个冲突插件且其中一个匹配