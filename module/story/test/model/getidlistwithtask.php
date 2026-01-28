#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getIdListWithTask();
timeout=0
cid=18538

- 执行101有3个任务关联需求1,2,3 @3
- 执行102有2个任务关联需求1,2 @2
- 执行ID为0，返回空数组 @0
- 负数执行ID，返回空数组 @0
- 执行103的任务都没有关联需求（story=0），返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$task = zenData('task');
$task->id->range('1-10');
$task->execution->range('101{3},102{2},103{2},999{3}');
$task->story->range('1,2,3,1,2,0,0,4,5,6');
$task->deleted->range('0{8},1{2}');
$task->status->range('wait{4},doing{3},done{3}');
$task->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$storyTest = new storyModelTest();

// 测试步骤1：正常情况 - 获取包含关联需求任务的执行的需求ID列表数量
r(count($storyTest->getIdListWithTaskTest(101))) && p() && e('3'); // 执行101有3个任务关联需求1,2,3

// 测试步骤2：正常情况 - 获取另一个执行的需求ID列表数量
r(count($storyTest->getIdListWithTaskTest(102))) && p() && e('2'); // 执行102有2个任务关联需求1,2

// 测试步骤3：边界值测试 - 无效执行ID（0）
r(count($storyTest->getIdListWithTaskTest(0))) && p() && e('0'); // 执行ID为0，返回空数组

// 测试步骤4：边界值测试 - 无效执行ID（负数）
r(count($storyTest->getIdListWithTaskTest(-1))) && p() && e('0'); // 负数执行ID，返回空数组

// 测试步骤5：业务规则测试 - 包含story字段为0的任务的执行
r(count($storyTest->getIdListWithTaskTest(103))) && p() && e('0'); // 执行103的任务都没有关联需求（story=0），返回空数组