#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::getAliasNames();
timeout=0
cid=15953

- 测试步骤1：正常获取from和join表的别名
 - 属性t1 @zt_bug
 - 属性t2 @zt_project
- 测试步骤2：测试仅有from表的情况属性task_alias @zt_task
- 测试步骤3：测试仅有join表的情况属性u @zt_user
- 测试步骤4：测试空statement的情况 @0
- 测试步骤5：测试无匹配模块名的情况 @0
- 测试步骤6：测试多个from表的情况
 - 属性b @zt_bug
 - 属性t @zt_task
- 测试步骤7：测试多个join表的情况
 - 属性u @zt_user
 - 属性d @zt_dept

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

// 测试步骤6：测试多个from表的情况
$statement6 = new stdclass();
$from6a = new stdclass();
$from6a->table = 'zt_bug';
$from6a->alias = 'b';
$from6b = new stdclass();
$from6b->table = 'zt_task';
$from6b->alias = 't';
$statement6->from = array($from6a, $from6b);
$moduleNames6 = array('zt_bug' => 'bug', 'zt_task' => 'task');

// 测试步骤7：测试多个join表的情况
$statement7 = new stdclass();
$join7a = new stdclass();
$join7a->expr = new stdclass();
$join7a->expr->table = 'zt_user';
$join7a->expr->alias = 'u';
$join7b = new stdclass();
$join7b->expr = new stdclass();
$join7b->expr->table = 'zt_dept';
$join7b->expr->alias = 'd';
$statement7->join = array($join7a, $join7b);
$moduleNames7 = array('zt_user' => 'user', 'zt_dept' => 'dept');

r($dataviewTest->getAliasNamesTest($statement1, $moduleNames1)) && p('t1,t2') && e('zt_bug,zt_project');              // 测试步骤1：正常获取from和join表的别名
r($dataviewTest->getAliasNamesTest($statement2, $moduleNames2)) && p('task_alias') && e('zt_task');              // 测试步骤2：测试仅有from表的情况
r($dataviewTest->getAliasNamesTest($statement3, $moduleNames3)) && p('u') && e('zt_user');              // 测试步骤3：测试仅有join表的情况
r(count($dataviewTest->getAliasNamesTest($statement4, $moduleNames4))) && p() && e('0');              // 测试步骤4：测试空statement的情况
r(count($dataviewTest->getAliasNamesTest($statement5, $moduleNames5))) && p() && e('0');              // 测试步骤5：测试无匹配模块名的情况
r($dataviewTest->getAliasNamesTest($statement6, $moduleNames6)) && p('b,t') && e('zt_bug,zt_task');              // 测试步骤6：测试多个from表的情况
r($dataviewTest->getAliasNamesTest($statement7, $moduleNames7)) && p('u,d') && e('zt_user,zt_dept');              // 测试步骤7：测试多个join表的情况