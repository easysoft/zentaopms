#!/usr/bin/env php
<?php

/**

title=测试 cneModel::backup();
timeout=0
cid=0

- 步骤1：正常实例备份，使用默认用户账号 @object
- 步骤2：正常实例备份，指定用户账号 @object
- 步骤3：正常实例备份，指定备份模式manual @object
- 步骤4：正常实例备份，指定备份模式system @object
- 步骤5：正常实例备份，指定备份模式upgrade @object

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

$cneTest = new cneTest();

// 测试步骤
r($cneTest->backupTest(1)) && p() && e('object'); // 步骤1：正常实例备份，使用默认用户账号
r($cneTest->backupTest(1, 'testuser')) && p() && e('object'); // 步骤2：正常实例备份，指定用户账号
r($cneTest->backupTest(1, null, 'manual')) && p() && e('object'); // 步骤3：正常实例备份，指定备份模式manual
r($cneTest->backupTest(2, 'admin', 'system')) && p() && e('object'); // 步骤4：正常实例备份，指定备份模式system
r($cneTest->backupTest(2, null, 'upgrade')) && p() && e('object'); // 步骤5：正常实例备份，指定备份模式upgrade