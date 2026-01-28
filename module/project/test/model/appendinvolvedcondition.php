#!/usr/bin/env php
<?php

/**

title=测试 projectModel::appendInvolvedCondition();
timeout=0
cid=17799

- 测试步骤1：正常查询语句添加参与条件 @1
- 测试步骤2：空查询语句添加参与条件 @1
- 测试步骤3：已有条件的查询语句添加参与条件 @1
- 测试步骤4：测试当前用户为项目创建者的条件 @1
- 测试步骤5：测试当前用户为项目经理的条件 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->type->range('project{5}');
$projectTable->openedBy->range('admin,user1,user2,admin,user3');
$projectTable->PM->range('user1,admin,user3,user2,admin');
$projectTable->whitelist->range('admin,user1,user2,user3,admin');
$projectTable->deleted->range('0{5}');
$projectTable->gen(5);

$teamTable = zenData('team');
$teamTable->id->range('1-10');
$teamTable->root->range('1-5:2');
$teamTable->type->range('project{10}');
$teamTable->account->range('admin{2},user1{2},user2{2},user3{2},user4{2}');
$teamTable->gen(10);

$stakeholderTable = zenData('stakeholder');
$stakeholderTable->id->range('1-8');
$stakeholderTable->objectID->range('1-4:2');
$stakeholderTable->objectType->range('project{8}');
$stakeholderTable->user->range('admin{2},user1{2},user2{2},user3{2}');
$stakeholderTable->deleted->range('0{8}');
$stakeholderTable->gen(8);

su('admin');

$projectTest = new projectModelTest();

r(!empty($projectTest->appendInvolvedConditionTest())) && p() && e('1'); // 测试步骤1：正常查询语句添加参与条件
r(!empty($projectTest->appendInvolvedConditionTest(null))) && p() && e('1'); // 测试步骤2：空查询语句添加参与条件

global $tester;
$stmt = $tester->dao->select('*')->from(TABLE_PROJECT)->alias('t1');
$stmt = $projectTest->objectModel->leftJoinInvolvedTable($stmt);
$stmt->where('t1.deleted')->eq(0);
r(!empty($projectTest->appendInvolvedConditionTest($stmt))) && p() && e('1'); // 测试步骤3：已有条件的查询语句添加参与条件

$stmt = $tester->dao->select('*')->from(TABLE_PROJECT)->alias('t1');
$stmt = $projectTest->objectModel->leftJoinInvolvedTable($stmt);
$result = $projectTest->appendInvolvedConditionTest($stmt);
$sql = $result->get();
r(strpos($sql, "t1.openedBy") !== false) && p() && e('1'); // 测试步骤4：测试当前用户为项目创建者的条件

$stmt = $tester->dao->select('*')->from(TABLE_PROJECT)->alias('t1');
$stmt = $projectTest->objectModel->leftJoinInvolvedTable($stmt);
$result = $projectTest->appendInvolvedConditionTest($stmt);
$sql = $result->get();
r(strpos($sql, "t1.PM") !== false) && p() && e('1'); // 测试步骤5：测试当前用户为项目经理的条件
