#!/usr/bin/env php
<?php

/**

title=测试 myZen::assignRelatedData();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性bugs @1
 - 属性stories @1
 - 属性todos @1
 - 属性tasks @1
 - 属性tickets @1
- 步骤2：空数据 @0
- 步骤3：单一类型属性bugs @1
- 步骤4：多类型混合
 - 属性bugs @2
 - 属性stories @1
- 步骤5：无关联结果
 - 属性bugs @0
 - 属性stories @0
 - 属性todos @0
 - 属性tasks @0
 - 属性tickets @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('feedback')->loadYaml('feedback_assignrelateddata', false, 2)->gen(10);

// 创建相关数据表的测试数据
$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->product->range('1-3');
$bugTable->title->range('测试Bug{5},功能Bug{5}');
$bugTable->status->range('active,resolved,closed');
$bugTable->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1-3');
$storyTable->title->range('用户故事{5},功能需求{5}');
$storyTable->status->range('draft,active,closed');
$storyTable->gen(10);

$todoTable = zenData('todo');
$todoTable->id->range('1-10');
$todoTable->account->range('admin,user1,user2');
$todoTable->name->range('待办事项{10}');
$todoTable->status->range('wait,doing,done');
$todoTable->gen(10);

$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->project->range('1-3');
$taskTable->name->range('测试任务{10}');
$taskTable->status->range('wait,doing,done');
$taskTable->gen(10);

// 注释掉ticket数据生成，避免数据库datetime字段错误
// $ticketTable = zenData('ticket');
// $ticketTable->id->range('1-10');
// $ticketTable->product->range('1-3');
// $ticketTable->title->range('工单标题{10}');
// $ticketTable->status->range('wait,replied,closed');
// $ticketTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$feedbacks1 = array();
for($i = 1; $i <= 5; $i++)
{
    $feedback = new stdClass();
    $feedback->id = $i;
    $feedback->solution = ($i == 1) ? 'tobug' : (($i == 2) ? 'tostory' : (($i == 3) ? 'totodo' : (($i == 4) ? 'totask' : 'ticket')));
    $feedback->result = $i;
    $feedbacks1[] = $feedback;
}

r($myTest->assignRelatedDataTest($feedbacks1)) && p('bugs,stories,todos,tasks,tickets') && e('1,1,1,1,1'); // 步骤1：正常情况

$emptyFeedbacks = array();
r($myTest->assignRelatedDataTest($emptyFeedbacks)) && p() && e('0'); // 步骤2：空数据

$singleFeedbacks = array();
$singleFeedback = new stdClass();
$singleFeedback->id = 1;
$singleFeedback->solution = 'tobug';
$singleFeedback->result = 1;
$singleFeedbacks[] = $singleFeedback;
r($myTest->assignRelatedDataTest($singleFeedbacks)) && p('bugs') && e('1'); // 步骤3：单一类型

$mixedFeedbacks = array();
for($i = 1; $i <= 3; $i++)
{
    $feedback = new stdClass();
    $feedback->id = $i;
    $feedback->solution = ($i == 1) ? 'tobug' : (($i == 2) ? 'tobug' : 'tostory');
    $feedback->result = $i;
    $mixedFeedbacks[] = $feedback;
}
r($myTest->assignRelatedDataTest($mixedFeedbacks)) && p('bugs,stories') && e('2,1'); // 步骤4：多类型混合

$noResultFeedbacks = array();
$noResultFeedback = new stdClass();
$noResultFeedback->id = 1;
$noResultFeedback->solution = 'other';
$noResultFeedback->result = 1;
$noResultFeedbacks[] = $noResultFeedback;
r($myTest->assignRelatedDataTest($noResultFeedbacks)) && p('bugs,stories,todos,tasks,tickets') && e('0,0,0,0,0'); // 步骤5：无关联结果