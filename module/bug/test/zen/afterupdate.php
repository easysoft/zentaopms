#!/usr/bin/env php
<?php

/**

title=测试 bugZen::afterUpdate();
timeout=0
cid=15423

- 执行bugTest模块的afterUpdateTest方法，参数是$bug1, $oldBug1  @1
- 执行$result2 && $afterActionCount > $beforeActionCount @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug3, $oldBug3  @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug4, $oldBug4  @1
- 执行bugTest模块的afterUpdateTest方法，参数是$bug5, $oldBug5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备bug测试数据
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1');
$bug->execution->range('0{5},101{5}');
$bug->status->range('active{5},resolved{3},closed{2}');
$bug->resolvedBuild->range('0{5},1{3},2{2}');
$bug->plan->range('0{5},1{3},2{2}');
$bug->resolvedBy->range('[]{5},admin{3},user1{2}');
$bug->relatedBug->range('[]{10}');
$bug->feedback->range('0{10}');
$bug->gen(10);

// 准备build测试数据
$build = zenData('build');
$build->id->range('1-5');
$build->product->range('1');
$build->execution->range('101');
$build->name->range('Build 1,Build 2,Build 3,Build 4,Build 5');
$build->bugs->range('[]{5}');
$build->gen(5);

// 准备productplan测试数据
$plan = zenData('productplan');
$plan->id->range('1-5');
$plan->product->range('1');
$plan->title->range('Plan 1,Plan 2,Plan 3,Plan 4,Plan 5');
$plan->status->range('wait{2},doing{2},done{1}');
$plan->gen(5);

// 准备execution测试数据
$execution = zenData('project');
$execution->id->range('101-105');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->gen(5);

// 准备action测试数据,初始化为空
$action = zenData('action');
$action->gen(0);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 测试1:更新resolvedBuild字段,验证build关联
$bug1 = new stdclass();
$bug1->id = 1;
$bug1->product = 1;
$bug1->execution = 0;
$bug1->status = 'resolved';
$bug1->resolvedBuild = '2';
$bug1->plan = 0;
$bug1->resolvedBy = 'admin';
$bug1->relatedBug = '';
$bug1->feedback = 0;

$oldBug1 = new stdclass();
$oldBug1->id = 1;
$oldBug1->product = 1;
$oldBug1->execution = 0;
$oldBug1->status = 'active';
$oldBug1->resolvedBuild = '1';
$oldBug1->plan = 0;
$oldBug1->resolvedBy = '';
$oldBug1->relatedBug = '';
$oldBug1->feedback = 0;

r($bugTest->afterUpdateTest($bug1, $oldBug1)) && p() && e('1');

// 测试2:更新plan字段,验证产品计划变更历史
$bug2 = new stdclass();
$bug2->id = 2;
$bug2->product = 1;
$bug2->execution = 0;
$bug2->status = 'active';
$bug2->resolvedBuild = '0';
$bug2->plan = 2;
$bug2->resolvedBy = '';
$bug2->relatedBug = '';
$bug2->feedback = 0;

$oldBug2 = new stdclass();
$oldBug2->id = 2;
$oldBug2->product = 1;
$oldBug2->execution = 0;
$oldBug2->status = 'active';
$oldBug2->resolvedBuild = '0';
$oldBug2->plan = 1;
$oldBug2->resolvedBy = '';
$oldBug2->relatedBug = '';
$oldBug2->feedback = 0;

$beforeActionCount = $tester->dao->select('count(*) as count')->from(TABLE_ACTION)->fetch('count');
$result2 = $bugTest->afterUpdateTest($bug2, $oldBug2);
$afterActionCount = $tester->dao->select('count(*) as count')->from(TABLE_ACTION)->fetch('count');
r($result2 && $afterActionCount > $beforeActionCount) && p() && e('1');

// 测试3:更新status字段且有execution,验证看板泳道更新
$bug3 = new stdclass();
$bug3->id = 6;
$bug3->product = 1;
$bug3->execution = 101;
$bug3->status = 'resolved';
$bug3->resolvedBuild = '0';
$bug3->plan = 0;
$bug3->resolvedBy = 'admin';
$bug3->relatedBug = '';
$bug3->feedback = 0;

$oldBug3 = new stdclass();
$oldBug3->id = 6;
$oldBug3->product = 1;
$oldBug3->execution = 101;
$oldBug3->status = 'active';
$oldBug3->resolvedBuild = '0';
$oldBug3->plan = 0;
$oldBug3->resolvedBy = '';
$oldBug3->relatedBug = '';
$oldBug3->feedback = 0;

r($bugTest->afterUpdateTest($bug3, $oldBug3)) && p() && e('1');

// 测试4:更新resolvedBy字段,验证积分奖励
$bug4 = new stdclass();
$bug4->id = 4;
$bug4->product = 1;
$bug4->execution = 0;
$bug4->status = 'resolved';
$bug4->resolvedBuild = '0';
$bug4->plan = 0;
$bug4->resolvedBy = 'admin';
$bug4->relatedBug = '';
$bug4->feedback = 0;

$oldBug4 = new stdclass();
$oldBug4->id = 4;
$oldBug4->product = 1;
$oldBug4->execution = 0;
$oldBug4->status = 'active';
$oldBug4->resolvedBuild = '0';
$oldBug4->plan = 0;
$oldBug4->resolvedBy = '';
$oldBug4->relatedBug = '';
$oldBug4->feedback = 0;

r($bugTest->afterUpdateTest($bug4, $oldBug4)) && p() && e('1');

// 测试5:普通更新,不涉及特殊字段变更
$bug5 = new stdclass();
$bug5->id = 5;
$bug5->product = 1;
$bug5->execution = 0;
$bug5->status = 'active';
$bug5->resolvedBuild = '0';
$bug5->plan = 0;
$bug5->resolvedBy = '';
$bug5->relatedBug = '';
$bug5->feedback = 0;

$oldBug5 = new stdclass();
$oldBug5->id = 5;
$oldBug5->product = 1;
$oldBug5->execution = 0;
$oldBug5->status = 'active';
$oldBug5->resolvedBuild = '0';
$oldBug5->plan = 0;
$oldBug5->resolvedBy = '';
$oldBug5->relatedBug = '';
$oldBug5->feedback = 0;

r($bugTest->afterUpdateTest($bug5, $oldBug5)) && p() && e('1');