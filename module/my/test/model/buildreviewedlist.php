#!/usr/bin/env php
<?php

/**

title=测试 myModel::buildReviewedList();
timeout=0
cid=17274

- 步骤1：正常情况 @2
- 步骤2：边界值 @0
- 步骤3：异常输入 @0
- 步骤4：权限验证 @1
- 步骤5：业务规则 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$action = zenData('action');
$action->id->range('1-10');
$action->objectType->range('story,testcase,bug,review,attend');
$action->objectID->range('1-5');
$action->actor->range('user1,user2,admin');
$action->action->range('reviewed,approvalreview,submitreview');
$action->date->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`');
$action->extra->range('pass,reject,');
$action->gen(10);

$story = zenData('story');
$story->id->range('1-5');
$story->title->range('测试需求1,测试需求2,测试需求3,测试需求4,测试需求5');
$story->type->range('story,requirement');
$story->status->range('active,reviewing,closed');
$story->product->range('1-3');
$story->gen(5);

$case = zenData('case');
$case->id->range('1-5');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->status->range('normal,wait');
$case->product->range('1-3');
$case->gen(5);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('测试缺陷1,测试缺陷2,测试缺陷3,测试缺陷4,测试缺陷5');
$bug->status->range('active,resolved,closed');
$bug->product->range('1-3');
$bug->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$myTest = new myModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($myTest->buildReviewedListTest(array('story' => array(1 => (object)array('id' => 1, 'title' => '测试需求1', 'type' => 'story', 'status' => 'active', 'product' => 1)), 'testcase' => array(2 => (object)array('id' => 2, 'title' => '测试用例1', 'status' => 'normal', 'product' => 1))), array((object)array('objectType' => 'story', 'objectID' => 1, 'date' => '2024-01-01 10:00:00', 'extra' => 'pass'), (object)array('objectType' => 'testcase', 'objectID' => 2, 'date' => '2024-01-02 11:00:00', 'extra' => 'reject')), array()))) && p() && e('2'); // 步骤1：正常情况
r(count($myTest->buildReviewedListTest(array(), array(), array()))) && p() && e('0'); // 步骤2：边界值
r(count($myTest->buildReviewedListTest(array('story' => array(1 => (object)array('id' => 1, 'title' => '测试需求1', 'type' => 'story', 'status' => 'active', 'product' => 1))), array((object)array('objectType' => 'story', 'objectID' => 999, 'date' => '2024-01-01 10:00:00', 'extra' => 'pass')), array()))) && p() && e('0'); // 步骤3：异常输入
r(count($myTest->buildReviewedListTest(array('workflow' => array(1 => (object)array('id' => 1, 'title' => '工作流对象', 'status' => 'done'))), array((object)array('objectType' => 'workflow', 'objectID' => 1, 'date' => '2024-01-01 10:00:00', 'extra' => 'pass')), array('workflow' => (object)array('titleField' => 'title', 'name' => '工作流'))))) && p() && e('1'); // 步骤4：权限验证
r(count($myTest->buildReviewedListTest(array('review' => array(1 => (object)array('id' => 1, 'title' => '项目评审', 'status' => 'done')), 'case' => array(2 => (object)array('id' => 2, 'title' => '测试用例', 'status' => 'normal')), 'attend' => array(3 => (object)array('id' => 3, 'account' => 'user1', 'date' => '2024-01-01', 'reviewStatus' => 'pass'))), array((object)array('objectType' => 'review', 'objectID' => 1, 'date' => '2024-01-01 10:00:00', 'extra' => 'pass'), (object)array('objectType' => 'case', 'objectID' => 2, 'date' => '2024-01-02 11:00:00', 'extra' => 'pass'), (object)array('objectType' => 'attend', 'objectID' => 3, 'date' => '2024-01-03 12:00:00', 'extra' => 'pass')), array()))) && p() && e('3'); // 步骤5：业务规则