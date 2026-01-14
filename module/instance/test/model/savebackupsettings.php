#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::saveBackupSettings();
timeout=0
cid=16815

- 步骤1：正常禁用自动备份 @1
- 步骤2：正常启用自动备份 @1
- 步骤3：超出范围的保留天数属性message @请输入1~30之间的整数
- 步骤4：无效时间格式属性message @无效的时间
- 步骤5：边界值测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$instance = zenData('instance');
$instance->id->range('1-5');
$instance->name->range('test-instance{5}');
$instance->chart->range('zentao,gitlab,jenkins,sonarqube,nexus3');
$instance->status->range('running{3},stopped{2}');
$instance->space->range('1-5');
$instance->autoBackup->range('0{3},1{2}');
$instance->backupKeepDays->range('1{2},7{2},30{1}');
$instance->deleted->range('0');
$instance->gen(5);

$cron = zenData('cron');
$cron->id->range('1-10');
$cron->m->range('0-59:R');
$cron->h->range('0-23:R');
$cron->dom->range('*');
$cron->mon->range('*');
$cron->dow->range('*');
$cron->command->range('moduleName=instance&methodName=cronBackup&instanceID=1,moduleName=instance&methodName=cronBackup&instanceID=2,other-command{8}');
$cron->remark->range('Backup Instance 1,Backup Instance 2,Other Task{8}');
$cron->type->range('zentao{2},system{8}');
$cron->status->range('normal{8},stop{2}');
$cron->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 模拟POST数据设置
global $_POST;

// 5. 强制要求：必须包含至少5个测试步骤
// 测试步骤1：正常保存备份设置（禁用自动备份）
$_POST = array(
    'autoBackup' => '0',
    'backupTime' => '1:00',
    'backupCycle' => '1',
    'backupKeepDays' => '7'
);
$instance1 = new stdClass();
$instance1->id = 1;
$instance1->backupKeepDays = 7;
r($instanceTest->saveBackupSettingsTest($instance1)) && p() && e('1'); // 步骤1：正常禁用自动备份

// 测试步骤2：正常保存备份设置（启用自动备份）
$_POST = array(
    'autoBackup' => '1',
    'backupTime' => '02:30',
    'backupCycle' => '1',
    'backupKeepDays' => '7'
);
$instance2 = new stdClass();
$instance2->id = 2;
$instance2->backupKeepDays = 7;
r($instanceTest->saveBackupSettingsTest($instance2)) && p() && e('1'); // 步骤2：正常启用自动备份

// 测试步骤3：无效的备份保留天数（超出范围）
$_POST = array(
    'autoBackup' => '1',
    'backupTime' => '1:00',
    'backupCycle' => '1',
    'backupKeepDays' => '50'
);
$instance3 = new stdClass();
$instance3->id = 3;
$instance3->backupKeepDays = 7;
r($instanceTest->saveBackupSettingsTest($instance3)) && p('message') && e('请输入1~30之间的整数'); // 步骤3：超出范围的保留天数

// 测试步骤4：无效的备份时间格式
$_POST = array(
    'autoBackup' => '1',
    'backupTime' => '25:80',
    'backupCycle' => '1',
    'backupKeepDays' => '7'
);
$instance4 = new stdClass();
$instance4->id = 4;
$instance4->backupKeepDays = 7;
r($instanceTest->saveBackupSettingsTest($instance4)) && p('message') && e('无效的时间'); // 步骤4：无效时间格式

// 测试步骤5：边界值测试（保留天数为30天）
$_POST = array(
    'autoBackup' => '1',
    'backupTime' => '23:59',
    'backupCycle' => '1',
    'backupKeepDays' => '30'
);
$instance5 = new stdClass();
$instance5->id = 5;
$instance5->backupKeepDays = 1;
r($instanceTest->saveBackupSettingsTest($instance5)) && p() && e('1'); // 步骤5：边界值测试