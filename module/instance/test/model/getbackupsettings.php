#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::getBackupSettings();
timeout=0
cid=16797

- 步骤1：正常情况获取备份时间属性backupTime @01:00
- 步骤2：有定时任务的实例获取备份时间属性backupTime @01:00
- 步骤3：不同时间的定时任务属性backupTime @08:30
- 步骤4：验证cycleDays固定为1属性cycleDays @1
- 步骤5：另一个时间的定时任务属性backupTime @23:45

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$instanceTable = zenData('instance');
$instanceTable->id->range('1-5');
$instanceTable->name->range('test-instance{5}');
$instanceTable->autoBackup->range('0{2},1{3}');
$instanceTable->backupKeepDays->range('1{2},7{2},30');
$instanceTable->space->range('1{5}');
$instanceTable->deleted->range('0{5}');
$instanceTable->gen(5);

$cronTable = zenData('cron');
$cronTable->id->range('1-3');
$cronTable->command->range('moduleName=instance&methodName=cronBackup&instanceID=3,moduleName=instance&methodName=cronBackup&instanceID=4,moduleName=instance&methodName=cronBackup&instanceID=5');
$cronTable->h->range('1,8,23');
$cronTable->m->range('0,30,45');
$cronTable->dom->range('*{3}');
$cronTable->mon->range('*{3}');
$cronTable->dow->range('*{3}');
$cronTable->type->range('zentao{3}');
$cronTable->status->range('normal{3}');
$cronTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($instanceTest->getBackupSettingsTest(1)) && p('backupTime') && e('01:00'); // 步骤1：正常情况获取备份时间
r($instanceTest->getBackupSettingsTest(3)) && p('backupTime') && e('01:00'); // 步骤2：有定时任务的实例获取备份时间
r($instanceTest->getBackupSettingsTest(4)) && p('backupTime') && e('08:30'); // 步骤3：不同时间的定时任务
r($instanceTest->getBackupSettingsTest(2)) && p('cycleDays') && e('1'); // 步骤4：验证cycleDays固定为1
r($instanceTest->getBackupSettingsTest(5)) && p('backupTime') && e('23:45'); // 步骤5：另一个时间的定时任务