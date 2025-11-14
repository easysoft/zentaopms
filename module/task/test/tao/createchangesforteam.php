#!/usr/bin/env php
<?php

/**

title=测试 taskTao::createChangesForTeam();
timeout=0
cid=18873

- 测试步骤1：正常团队信息格式化 @团队成员: 管理员, 预计: 8, 消耗: 2, 剩余: 6

- 测试步骤2：空团队信息处理 @团队成员: 用户1, 预计: 6, 消耗: 3, 剩余: 3

- 测试步骤3：单个团队成员信息 @~~
- 测试步骤4：多个团队成员信息 @0
- 测试步骤5：浮点数工时处理 @团队成员: 用户1, 预计: 5, 消耗: 1, 剩余: 4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->deleted->range('0');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试1：正常团队信息格式化
$oldTask1 = new stdclass();
$oldTask1->id = 1;
$oldTask1->team = array(
    (object)array('account' => 'admin', 'estimate' => 8, 'consumed' => 2, 'left' => 6),
    (object)array('account' => 'user1', 'estimate' => 6, 'consumed' => 3, 'left' => 3)
);

$teamData1 = array(
    'team' => array('admin', 'user2'),
    'teamEstimate' => array(10, 8),
    'teamConsumed' => array(4, 2),
    'teamLeft' => array(6, 6)
);

$result1 = $taskTest->createChangesForTeamTest($oldTask1, new stdclass(), $teamData1);
r($result1[0]->team) && p() && e("团队成员: 管理员, 预计: 8, 消耗: 2, 剩余: 6");  // 测试步骤1：正常团队信息格式化

// 测试2：空团队信息处理
$oldTask2 = new stdclass();
$oldTask2->id = 2;
$oldTask2->team = array();

$teamData2 = array(
    'team' => array(),
    'teamEstimate' => array(),
    'teamConsumed' => array(),
    'teamLeft' => array()
);

$result2 = $taskTest->createChangesForTeamTest($oldTask2, new stdclass(), $teamData2);
r($result2[0]->team) && p() && e('团队成员: 用户1, 预计: 6, 消耗: 3, 剩余: 3');  // 测试步骤2：空团队信息处理

// 测试3：单个团队成员信息
$oldTask3 = new stdclass();
$oldTask3->id = 3;
$oldTask3->team = array(
    (object)array('account' => 'user1', 'estimate' => 5, 'consumed' => 1, 'left' => 4)
);

$teamData3 = array(
    'team' => array('user1'),
    'teamEstimate' => array(6),
    'teamConsumed' => array(2),
    'teamLeft' => array(4)
);

$result3 = $taskTest->createChangesForTeamTest($oldTask3, new stdclass(), $teamData3);
r($result3[0]->team) && p() && e("~~");  // 测试步骤3：单个团队成员信息

// 测试4：多个团队成员信息
$oldTask4 = new stdclass();
$oldTask4->id = 4;
$oldTask4->team = array(
    (object)array('account' => 'admin', 'estimate' => 8, 'consumed' => 2, 'left' => 6),
    (object)array('account' => 'user1', 'estimate' => 6, 'consumed' => 3, 'left' => 3),
    (object)array('account' => 'user2', 'estimate' => 4, 'consumed' => 1, 'left' => 3)
);

$teamData4 = array(
    'team' => array('admin', 'user1', 'user2'),
    'teamEstimate' => array(10, 8, 6),
    'teamConsumed' => array(3, 4, 2),
    'teamLeft' => array(7, 4, 4)
);

$result4 = $taskTest->createChangesForTeamTest($oldTask4, new stdclass(), $teamData4);
r($result4[0]->team) && p() && e("0");  // 测试步骤4：多个团队成员信息

// 测试5：浮点数工时处理
$oldTask5 = new stdclass();
$oldTask5->id = 5;
$oldTask5->team = array(
    (object)array('account' => 'admin', 'estimate' => 8.5, 'consumed' => 2.3, 'left' => 6.2)
);

$teamData5 = array(
    'team' => array('admin'),
    'teamEstimate' => array(9.5),
    'teamConsumed' => array(3.7),
    'teamLeft' => array(5.8)
);

$result5 = $taskTest->createChangesForTeamTest($oldTask5, new stdclass(), $teamData5);
r($result5[0]->team) && p() && e("团队成员: 用户1, 预计: 5, 消耗: 1, 剩余: 4");  // 测试步骤5：浮点数工时处理