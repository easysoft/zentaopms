#!/usr/bin/env php
<?php

/**

title=测试 personnelModel::getInvolvedProjects();
cid=17330

- 测试步骤1：传入多个有效项目ID >> 期望返回正确的账户项目统计
- 测试步骤2：传入空项目数组 >> 期望返回空数组
- 测试步骤3：传入不存在的项目ID >> 期望返回空数组
- 测试步骤4：传入单个项目ID >> 期望返回该项目的成员统计
- 测试步骤5：验证多项目成员计数准确性 >> 期望同一账户在多个项目中的计数正确

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/personnel.unittest.class.php';

$teamTable = zenData('team');
$teamTable->id->range('1-12');
$teamTable->root->range('11,12,13,11,12,14,15,16,11,12,13,14');
$teamTable->type->range('project{12}');
$teamTable->account->range('admin,admin,admin,user1,user1,user2,user2,user2,user3,user3,user3,test');
$teamTable->role->range('manager{4},developer{4},tester{4}');
$teamTable->gen(12);

$projectTable = zenData('project');
$projectTable->id->range('11-20');
$projectTable->type->range('project{10}');
$projectTable->name->range('项目A,项目B,项目C,项目D,项目E,项目F,项目G,项目H,项目I,项目J');
$projectTable->gen(10);

su('admin');

$personnelTest = new personnelTest();

r($personnelTest->getInvolvedProjectsTest(array(11, 12, 13))) && p('admin,user1,user3') && e('3,2,3');
r($personnelTest->getInvolvedProjectsTest(array())) && p() && e('0');
r($personnelTest->getInvolvedProjectsTest(array(999, 998))) && p() && e('0');
r($personnelTest->getInvolvedProjectsTest(array(11))) && p('admin,user1,user3') && e('1,1,1');
r($personnelTest->getInvolvedProjectsTest(array(14, 15, 16))) && p('user2,test') && e('3,1');