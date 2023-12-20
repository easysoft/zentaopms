#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->updatePivotStage();
timeout=0
cid=1

- 测试阶段为草稿的情况属性stage @draft
- 测试sql为空时阶段是否为草稿属性stage @draft
- 测试sql错误时阶段是否被修改为草稿属性stage @draft
- 测试查询重复的数据列时是否被修改为草稿属性stage @draft
- 测试数据正确时阶段是否没有被修改属性stage @published

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->gen(5);
su('admin');

$testStageDraft = array();
$testStageDraft['id']    = 1000;
$testStageDraft['stage'] = 'draft';

$testSQLEmpty = array();
$testSQLEmpty['id']    = 1001;
$testSQLEmpty['stage'] = 'draft';
$testSQLEmpty['sql']   = '';

$testErrorSQL = array();
$testErrorSQL['id']  = 1002;
$testErrorSQL['sql'] = 'select from';

$testRepeatColumn = array();
$testRepeatColumn['id']  = 1003;
$testRepeatColumn['sql'] = 'select id,id from zt_pivot;';

$testNormalData = array();
$testNormalData['id'] = 1007;

$upgrade = new upgradeTest();
r($upgrade->updatePivotStageTest($testStageDraft))   && p('stage') && e('draft');     //测试阶段为草稿的情况
r($upgrade->updatePivotStageTest($testSQLEmpty))     && p('stage') && e('draft');     //测试sql为空时阶段是否为草稿
r($upgrade->updatePivotStageTest($testErrorSQL))     && p('stage') && e('draft');     //测试sql错误时阶段是否被修改为草稿
r($upgrade->updatePivotStageTest($testRepeatColumn)) && p('stage') && e('draft');     //测试查询重复的数据列时是否被修改为草稿
r($upgrade->updatePivotStageTest($testNormalData))   && p('stage') && e('published'); //测试数据正确时阶段是否没有被修改
