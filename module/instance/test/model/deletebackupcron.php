#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::deleteBackupCron();
timeout=0
cid=16789

- 步骤1：正常情况 @1
- 步骤2：不存在的ID @1
- 步骤3：边界值0 @1
- 步骤4：负数ID @1
- 步骤5：空ID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

zendata('cron')->loadYaml('cron_deletebackupcron', false, 2)->gen(10);
zendata('instance')->loadYaml('instance_deletebackupcron', false, 2)->gen(5);

su('admin');

$instanceTest = new instanceTest();

// 创建测试用的instance对象
$instance1 = new stdClass();
$instance1->id = 1;

$instance2 = new stdClass(); 
$instance2->id = 999;

$instance3 = new stdClass();
$instance3->id = 0;

$instance4 = new stdClass();
$instance4->id = -1;

$instance5 = new stdClass();
$instance5->id = '';

r($instanceTest->deleteBackupCronTest($instance1)) && p() && e(1);    // 步骤1：正常情况
r($instanceTest->deleteBackupCronTest($instance2)) && p() && e(1);    // 步骤2：不存在的ID
r($instanceTest->deleteBackupCronTest($instance3)) && p() && e(1);    // 步骤3：边界值0
r($instanceTest->deleteBackupCronTest($instance4)) && p() && e(1);    // 步骤4：负数ID
r($instanceTest->deleteBackupCronTest($instance5)) && p() && e(1);    // 步骤5：空ID