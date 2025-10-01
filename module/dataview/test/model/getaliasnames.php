#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::getAliasNames();
timeout=0
cid=0

- 测试步骤1：正常获取from和join表的别名 @2
- 测试步骤2：测试仅有from表的情况 @1
- 测试步骤3：测试仅有join表的情况 @1
- 测试步骤4：测试空statement的情况 @0
- 测试步骤5：测试无匹配模块名的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

su('admin');

$dataviewTest = new dataviewTest();

// 测试步骤1：正常获取from和join表的别名
$statement1 = new stdclass();
$from1 = new stdclass();
$from1->table = 'zt_bug';
$from1->alias = 't1';
$statement1->from = array($from1);
$join1 = new stdclass();
$join1->expr = new stdclass();
$join1->expr->table = 'zt_project';
$join1->expr->alias = 't2';
$statement1->join = array($join1);
$moduleNames1 = array('zt_bug' => 'bug', 'zt_project' => 'project');

// 测试步骤2：测试仅有from表的情况
$statement2 = new stdclass();
$from2 = new stdclass();
$from2->table = 'zt_task';
$from2->alias = 'task_alias';
$statement2->from = array($from2);
$moduleNames2 = array('zt_task' => 'task');

// 测试步骤3：测试仅有join表的情况
$statement3 = new stdclass();
$join3 = new stdclass();
$join3->expr = new stdclass();
$join3->expr->table = 'zt_user';
$join3->expr->alias = 'u';
$statement3->join = array($join3);
$moduleNames3 = array('zt_user' => 'user');

// 测试步骤4：测试空statement的情况
$statement4 = new stdclass();
$moduleNames4 = array('zt_bug' => 'bug');

// 测试步骤5：测试无匹配模块名的情况
$statement5 = new stdclass();
$from5 = new stdclass();
$from5->table = 'unknown_table';
$from5->alias = 'unknown_alias';
$statement5->from = array($from5);
$moduleNames5 = array('zt_bug' => 'bug');

r(count($dataviewTest->getAliasNamesTest($statement1, $moduleNames1))) && p() && e('2');              // 测试步骤1：正常获取from和join表的别名
r(count($dataviewTest->getAliasNamesTest($statement2, $moduleNames2))) && p() && e('1');              // 测试步骤2：测试仅有from表的情况
r(count($dataviewTest->getAliasNamesTest($statement3, $moduleNames3))) && p() && e('1');              // 测试步骤3：测试仅有join表的情况
r(count($dataviewTest->getAliasNamesTest($statement4, $moduleNames4))) && p() && e('0');              // 测试步骤4：测试空statement的情况
r(count($dataviewTest->getAliasNamesTest($statement5, $moduleNames5))) && p() && e('0');              // 测试步骤5：测试无匹配模块名的情况