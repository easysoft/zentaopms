#!/usr/bin/env php
<?php

/**

title=测试 reportModel::getUserYearContributionCount();
timeout=0
cid=0

- 步骤1：正常情况 @3
- 步骤2：空用户账号数组 @3
- 步骤3：不存在用户 @0
- 步骤4：无效年份 @0
- 步骤5：指定用户2024年 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 2. zendata数据准备（手动插入测试数据）
global $tester;
$tester->dao->delete()->from(TABLE_ACTION)->where('id')->gt(100)->exec();

// 插入一些测试数据
$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'task',
    'objectID' => '1',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-01-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'story',
    'objectID' => '2', 
    'action' => 'opened',
    'actor' => 'user1',
    'date' => '2024-06-15 10:00:00',
    'comment' => 'test'
))->exec();

$tester->dao->insert(TABLE_ACTION)->data(array(
    'objectType' => 'bug',
    'objectID' => '3',
    'action' => 'opened',
    'actor' => 'admin',
    'date' => '2024-12-15 10:00:00',
    'comment' => 'test'
))->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$reportTest = new reportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($reportTest->getUserYearContributionCountTest(array('admin', 'user1'), '2024')) && p() && e('3'); // 步骤1：正常情况
r($reportTest->getUserYearContributionCountTest(array(), '2024')) && p() && e('3'); // 步骤2：空用户账号数组
r($reportTest->getUserYearContributionCountTest(array('nonexistent'), '2024')) && p() && e('0'); // 步骤3：不存在用户
r($reportTest->getUserYearContributionCountTest(array('admin'), '2000')) && p() && e('0'); // 步骤4：无效年份  
r($reportTest->getUserYearContributionCountTest(array('admin'), '2024')) && p() && e('2'); // 步骤5：指定用户2024年