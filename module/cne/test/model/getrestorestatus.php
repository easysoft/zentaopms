#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getRestoreStatus();
timeout=0
cid=15622

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
- 步骤5：负数实例ID查询恢复状态
 - 属性code @404
 - 属性message @Instance not found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

// 创建模拟实例对象
$instance1 = new stdclass();
$instance1->id = 1;
$instance1->k8name = 'test-app-1';
$instance1->spaceData = new stdclass();
$instance1->spaceData->k8space = 'test-namespace';
$instance1->channel = 'stable';

$instance999 = new stdclass();
$instance999->id = 999;
$instance999->k8name = 'test-app-999';
$instance999->spaceData = new stdclass();
$instance999->spaceData->k8space = 'test-namespace';
$instance999->channel = 'stable';

$instance0 = new stdclass();
$instance0->id = 0;
$instance0->k8name = 'test-app-0';
$instance0->spaceData = new stdclass();
$instance0->spaceData->k8space = 'test-namespace';
$instance0->channel = 'stable';

$instanceNeg = new stdclass();
$instanceNeg->id = -1;
$instanceNeg->k8name = 'test-app-neg';
$instanceNeg->spaceData = new stdclass();
$instanceNeg->spaceData->k8space = 'test-namespace';
$instanceNeg->channel = 'stable';

r($cneTest->getRestoreStatusTest($instance1, 'backup-restore-001')) && p('code,message') && e('600,CNE服务器出错');       // 步骤1：正常实例和有效备份名查询恢复状态
r($cneTest->getRestoreStatusTest($instance999, 'backup-test'))      && p('code,message') && e('404,Instance not found'); // 步骤2：不存在的实例ID查询恢复状态
r($cneTest->getRestoreStatusTest($instance0, 'backup-test'))        && p('code,message') && e('404,Instance not found'); // 步骤3：无效实例ID（0）查询恢复状态
r($cneTest->getRestoreStatusTest($instance1, ''))                   && p('code,message') && e('400,Backup name cannot be empty'); // 步骤4：空备份名查询恢复状态
r($cneTest->getRestoreStatusTest($instanceNeg, 'backup-test'))      && p('code,message') && e('404,Instance not found'); // 步骤5：负数实例ID查询恢复状态