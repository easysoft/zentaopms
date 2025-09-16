#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getRestoreStatus();
timeout=0
cid=0

- 步骤1：正常实例和有效备份名查询恢复状态
 - 属性code @600
 - 属性message @CNE服务器出错
- 步骤2：不存在的实例ID查询恢复状态
 - 属性code @404
 - 属性message @Instance not found
- 步骤3：无效实例ID（0）查询恢复状态
 - 属性code @404
 - 属性message @Instance not found
- 步骤4：空备份名查询恢复状态
 - 属性code @400
 - 属性message @Backup name cannot be empty
- 步骤5：无效实例ID（999）查询恢复状态
 - 属性code @404
 - 属性message @Instance not found

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
r($cneTest->getRestoreStatusTest(1, 'backup-restore-001')) && p('code,message') && e('600,CNE服务器出错');               // 步骤1：正常实例和有效备份名查询恢复状态
r($cneTest->getRestoreStatusTest(999, 'backup-test'))      && p('code,message') && e('404,Instance not found');          // 步骤2：不存在的实例ID查询恢复状态
r($cneTest->getRestoreStatusTest(0, 'backup-test'))        && p('code,message') && e('404,Instance not found');          // 步骤3：无效实例ID（0）查询恢复状态
r($cneTest->getRestoreStatusTest(1, ''))                   && p('code,message') && e('400,Backup name cannot be empty'); // 步骤4：空备份名查询恢复状态
r($cneTest->getRestoreStatusTest(999, 'backup-test'))      && p('code,message') && e('404,Instance not found');          // 步骤5：无效实例ID（999）查询恢复状态