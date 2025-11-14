#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoryForChange();
timeout=0
cid=18670

- 步骤1：正常变更需求内容属性version @2
- 步骤2：变更需求标题属性version @2
- 步骤3：变更评审人员属性version @2
- 步骤4：未变更内容属性version @1
- 步骤5：缺少必填评审人员属性reviewer @『评审人员』不能为空。

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1{10}');
$story->type->range('story{10}');
$story->status->range('active{10}');
$story->stage->range('wait{10}');
$story->version->range('1{10}');
$story->lastEditedBy->range('admin{10}');
$story->lastEditedDate->range('`2024-01-01 10:00:00`');
$story->reviewedBy->range('user1{10}');
$story->changedBy->range('{10}');
$story->closedBy->range('{10}');
$story->closedReason->range('{10}');
$story->gen(10);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1{10}');
$storyspec->title->range('需求标题1,需求标题2,需求标题3{8}');
$storyspec->spec->range('需求描述1,需求描述2,需求描述3{8}');
$storyspec->verify->range('验收标准1,验收标准2,验收标准3{8}');
$storyspec->gen(10);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-10');
$storyreview->version->range('1{10}');
$storyreview->reviewer->range('user1,user2,user3{8}');
$storyreview->result->range('pass{10}');
$storyreview->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyZenTest();

// 5. 测试步骤（必须至少5个）
// 步骤1：正常变更需求内容，版本号应增加
r($storyTest->buildStoryForChangeTest(1, array('spec' => '新的需求描述', 'verify' => '新的验收标准', 'title' => '需求标题1', 'lastEditedDate' => '2024-01-01 10:00:00', 'comment' => '变更说明', 'reviewer' => array('user1'), 'needNotReview' => 0, 'uid' => uniqid()))) && p('version') && e('2'); // 步骤1：正常变更需求内容
r($storyTest->buildStoryForChangeTest(2, array('spec' => '需求描述2', 'verify' => '验收标准2', 'title' => '新的需求标题', 'lastEditedDate' => '2024-01-01 10:00:00', 'comment' => '变更说明', 'reviewer' => array('user1'), 'needNotReview' => 0, 'uid' => uniqid()))) && p('version') && e('2'); // 步骤2：变更需求标题
r($storyTest->buildStoryForChangeTest(3, array('spec' => '需求描述3', 'verify' => '验收标准3', 'title' => '需求标题3', 'lastEditedDate' => '2024-01-01 10:00:00', 'comment' => '变更说明', 'reviewer' => array('user2'), 'needNotReview' => 0, 'uid' => uniqid()))) && p('version') && e('2'); // 步骤3：变更评审人员
r($storyTest->buildStoryForChangeTest(4, array('spec' => '需求描述3', 'verify' => '验收标准3', 'title' => '需求标题3', 'lastEditedDate' => '2024-01-01 10:00:00', 'comment' => '变更说明', 'reviewer' => array('user3'), 'needNotReview' => 0, 'uid' => uniqid()))) && p('version') && e('1'); // 步骤4：未变更内容
r($storyTest->buildStoryForChangeTest(5, array('spec' => '新的需求描述', 'verify' => '新的验收标准', 'title' => '需求标题3', 'lastEditedDate' => '2024-01-01 10:00:00', 'comment' => '变更说明', 'reviewer' => array(), 'needNotReview' => 0, 'uid' => uniqid()))) && p('reviewer') && e('『评审人员』不能为空。'); // 步骤5：缺少必填评审人员