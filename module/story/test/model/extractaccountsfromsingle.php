#!/usr/bin/env php
<?php

/**

title=测试 storyModel::extractAccountsFromSingle();
timeout=0
cid=18491

- 步骤1：包含全部不同账号字段 @4
- 步骤2：只包含openedBy字段 @1
- 步骤3：包含重复账号需要去重 @2
- 步骤4：包含空值和null值 @2
- 步骤5：空对象返回空数组 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备
$table = zenData('story');
$table->id->range('1-20');
$table->openedBy->range('admin,user1,user2,user3,dev1,dev2,dev3,dev4,pm1,pm2');
$table->assignedTo->range('user1,user2,user3,dev1,dev2,dev3,dev4,,closed');
$table->closedBy->range('admin,pm1,pm2,,');
$table->lastEditedBy->range('admin,user1,user2,user3,dev1,dev2,dev3,dev4,pm1,pm2');
$table->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 5. 测试步骤（至少5个）
r(count($storyTest->extractAccountsFromSingleTest((object)array('openedBy' => 'admin', 'assignedTo' => 'user1', 'closedBy' => 'pm1', 'lastEditedBy' => 'dev1')))) && p() && e('4'); // 步骤1：包含全部不同账号字段
r(count($storyTest->extractAccountsFromSingleTest((object)array('openedBy' => 'user2', 'assignedTo' => '', 'closedBy' => '', 'lastEditedBy' => '')))) && p() && e('1'); // 步骤2：只包含openedBy字段
r(count($storyTest->extractAccountsFromSingleTest((object)array('openedBy' => 'admin', 'assignedTo' => 'admin', 'closedBy' => 'admin', 'lastEditedBy' => 'user1')))) && p() && e('2'); // 步骤3：包含重复账号需要去重
r(count($storyTest->extractAccountsFromSingleTest((object)array('openedBy' => 'user3', 'assignedTo' => '', 'closedBy' => null, 'lastEditedBy' => 'dev2')))) && p() && e('2'); // 步骤4：包含空值和null值
r(count($storyTest->extractAccountsFromSingleTest((object)array()))) && p() && e('0'); // 步骤5：空对象返回空数组