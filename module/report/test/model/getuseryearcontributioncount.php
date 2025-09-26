#!/usr/bin/env php
<?php

/**

title=测试 reportModel::getUserYearContributionCount();
timeout=0
cid=0

- 步骤1：正常情况-多个用户2024年 @6
- 步骤2：空用户账号数组-所有用户2024年 @6
- 步骤3：不存在用户2024年 @0
- 步骤4：无效年份2000年 @0
- 步骤5：指定admin用户2024年 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. zendata数据准备（清理并插入测试数据）
global $tester;
$tester->dao->delete()->from(TABLE_ACTION)->where('LEFT(date, 4)')->eq('2024')->exec();

// 插入测试数据 - 6个opened动作
$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => '1',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-01-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => '2',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-02-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => '3',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-03-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'story',
    'objectID' => '4',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-04-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'story',
    'objectID' => '5',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-05-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'bug',
    'objectID' => '6',
    'action' => 'opened',
    'actor' => 'user1',
    'date' => '2024-06-15 10:00:00',
    'comment' => 'test'
))->exec();

// 插入一些不会被统计的动作
$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => '7',
    'action' => 'edited', // 这个不在contributionCount配置中
    'actor' => 'admin',
    'date' => '2024-07-15 10:00:00',
    'comment' => 'test'
))->exec();

// 插入非2024年的数据
$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => '8',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2023-01-15 10:00:00',
    'comment' => 'test'
))->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($reportTest->getUserYearContributionCountTest(array('admin', 'user1'), '2024')) && p() && e('6'); // 步骤1：正常情况-多个用户2024年
r($reportTest->getUserYearContributionCountTest(array(), '2024')) && p() && e('6'); // 步骤2：空用户账号数组-所有用户2024年
r($reportTest->getUserYearContributionCountTest(array('nonexistent'), '2024')) && p() && e('0'); // 步骤3：不存在用户2024年
r($reportTest->getUserYearContributionCountTest(array('admin'), '2000')) && p() && e('0'); // 步骤4：无效年份2000年
r($reportTest->getUserYearContributionCountTest(array('admin'), '2024')) && p() && e('5'); // 步骤5：指定admin用户2024年