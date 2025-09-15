#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupStatus();
timeout=0
cid=0

- 步骤1：正常情况，返回对象 @object
- 步骤2：不存在的实例ID属性code @404
- 步骤3：空的备份名称 @object
- 步骤4：特殊字符的备份名称 @object
- 步骤5：无效的实例ID属性message @Instance not found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// zendata数据准备
zenData('instance')->loadYaml('instance', false, 2)->gen(2);
zenData('space')->loadYaml('space', false, 1)->gen(1);

global $tester;
$tester->app->user = new stdclass();
$tester->app->user->account = 'admin';

su('admin');

// 创建测试实例
$cneTest = new cneTest();

// 测试步骤
r($cneTest->getBackupStatusTest(1, 'backup-20241207-001')) && p() && e('object'); // 步骤1：正常情况，返回对象
r($cneTest->getBackupStatusTest(999, 'backup-20241207-001')) && p('code') && e('404'); // 步骤2：不存在的实例ID
r($cneTest->getBackupStatusTest(1, '')) && p() && e('object'); // 步骤3：空的备份名称
r($cneTest->getBackupStatusTest(1, 'backup-test@#$%')) && p() && e('object'); // 步骤4：特殊字符的备份名称
r($cneTest->getBackupStatusTest(0, 'backup-20241207-001')) && p('message') && e('Instance not found'); // 步骤5：无效的实例ID