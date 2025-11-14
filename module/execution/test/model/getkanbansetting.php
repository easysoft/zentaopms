#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
su('admin');

/**

title=测试 executionModel::getKanbanSetting();
cid=16321

- 测试步骤1：验证colorList中wait颜色配置 >> 期望包含'#7EC5FF'
- 测试步骤2：验证colorList总数量 >> 期望为6个状态颜色
- 测试步骤3：验证默认allCols属性值 >> 期望为'1'
- 测试步骤4：验证默认showOption属性值 >> 期望为'0'
- 测试步骤5：验证返回对象属性完整性 >> 期望包含必需属性

*/

$count = array('0', '1');

$execution = new executionTest();
r($execution->getKanbanSettingTest($count[0])) && p('colorList:wait') && e('~f:7EC5FF$~'); // 步骤1：验证colorList中wait颜色配置
r($execution->getKanbanSettingTest($count[1])) && p()                 && e('6');           // 步骤2：验证colorList总数量
r($execution->getKanbanSettingTest('allCols')) && p()                 && e('1');           // 步骤3：验证默认allCols属性值
r($execution->getKanbanSettingTest('showOption')) && p()              && e('0');           // 步骤4：验证默认showOption属性值
r($execution->getKanbanSettingTest('properties')) && p()              && e('allCols,showOption,colorList'); // 步骤5：验证返回对象属性完整性