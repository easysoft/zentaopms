#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoryForChange();
timeout=0
cid=0

- 执行storyTest模块的buildStoryForChangeTest方法，参数是1
 - 属性title @变更后的需求标题
 - 属性lastEditedBy @admin
- 执行storyTest模块的buildStoryForChangeTest方法，参数是2 属性title @变更后的需求标题2
- 执行storyTest模块的buildStoryForChangeTest方法，参数是3  @alse
- 执行storyTest模块的buildStoryForChangeTest方法，参数是4  @alse
- 执行storyTest模块的buildStoryForChangeTest方法，参数是5  @alse
- 执行storyTest模块的buildStoryForChangeTest方法，参数是6 属性version @2
- 执行storyTest模块的buildStoryForChangeTest方法，参数是7 属性status @active

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1{5}, 2{3}, 3{2}');
$story->branch->range('0{8}, 1{2}');
$story->module->range('0{6}, 1801{2}, 1802{2}');
$story->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10');
$story->type->range('story{8}, requirement{2}');
$story->pri->range('1{2}, 2{3}, 3{3}, 4{2}');
$story->status->range('active{5}, changing{3}, developing{2}');
$story->stage->range('wait{3}, planned{2}, projected{2}, developing{1}, developed{1}, testing{1}');
$story->openedBy->range('admin{5}, user1{3}, user2{2}');
$story->assignedTo->range('admin{3}, user1{2}, user2{2}, \'\'{3}');
$story->lastEditedBy->range('admin{5}, user1{3}, user2{2}');
$story->lastEditedDate->range('`2023-01-01 00:00:00`');
$story->reviewedBy->range('admin{3}, user1{2}, user2{2}, \'\'{3}');
$story->version->range('1{6}, 2{3}, 3{1}');
$story->gen(10);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1{6}, 2{3}, 3{1}');
$storyspec->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10');
$storyspec->spec->range('这是需求规格说明1,这是需求规格说明2,这是需求规格说明3,这是需求规格说明4,这是需求规格说明5,这是需求规格说明6,这是需求规格说明7,这是需求规格说明8,这是需求规格说明9,这是需求规格说明10');
$storyspec->verify->range('这是验收条件1,这是验收条件2,这是验收条件3,这是验收条件4,这是验收条件5,这是验收条件6,这是验收条件7,这是验收条件8,这是验收条件9,这是验收条件10');
$storyspec->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
global $app;
$app->user->account = 'admin';

// 步骤1：正常情况下构建需求变更数据
$_POST = array(
    'title' => '变更后的需求标题',
    'spec' => '变更后的规格说明',
    'verify' => '变更后的验收条件',
    'comment' => '变更原因',
    'reviewer' => array('user1'),
    'needNotReview' => '0',
    'lastEditedDate' => '2023-01-01 00:00:00'
);
r($storyTest->buildStoryForChangeTest(1)) && p('title,lastEditedBy') && e('变更后的需求标题,admin');

// 步骤2：测试空的lastEditedDate正常处理
$_POST = array(
    'title' => '变更后的需求标题2',
    'spec' => '变更后的规格说明2',
    'verify' => '变更后的验收条件2',
    'comment' => '变更原因2',
    'reviewer' => array('user1'),
    'needNotReview' => '0',
    'lastEditedDate' => ''
);
r($storyTest->buildStoryForChangeTest(2)) && p('title') && e('变更后的需求标题2');

// 步骤3：测试必填字段spec为空的验证
$_POST = array(
    'title' => '变更后的需求标题3',
    'spec' => '',
    'verify' => '验收条件',
    'comment' => '变更原因',
    'reviewer' => array('user1'),
    'needNotReview' => '0',
    'lastEditedDate' => '2023-01-01 00:00:00'
);
r($storyTest->buildStoryForChangeTest(3)) && p() && e(false);

// 步骤4：测试必填字段comment为空的验证
$_POST = array(
    'title' => '变更后的需求标题4',
    'spec' => '规格说明4',
    'verify' => '验收条件4',
    'comment' => '',
    'reviewer' => array('user1'),
    'needNotReview' => '0',
    'lastEditedDate' => '2023-01-01 00:00:00'
);
r($storyTest->buildStoryForChangeTest(4)) && p() && e(false);

// 步骤5：测试reviewer为空的验证
$_POST = array(
    'title' => '变更后的需求标题5',
    'spec' => '规格说明5',
    'verify' => '验收条件5',
    'comment' => '变更原因5',
    'reviewer' => array(),
    'needNotReview' => '0',
    'lastEditedDate' => '2023-01-01 00:00:00'
);
r($storyTest->buildStoryForChangeTest(5)) && p() && e(false);

// 步骤6：测试规格内容发生变化时的版本递增
$_POST = array(
    'title' => '需求标题6变更版',
    'spec' => '完全不同的规格说明',
    'verify' => '完全不同的验收条件',
    'comment' => '规格发生重大变更',
    'reviewer' => array('user1'),
    'needNotReview' => '0',
    'lastEditedDate' => '2023-01-01 00:00:00'
);
r($storyTest->buildStoryForChangeTest(6)) && p('version') && e(2);

// 步骤7：测试规格内容未变化时的状态保持
$_POST = array(
    'title' => '需求标题7',
    'spec' => '这是需求规格说明7',
    'verify' => '这是验收条件7',
    'comment' => '仅修改其他字段',
    'reviewer' => array('user1'),
    'needNotReview' => '0',
    'lastEditedDate' => '2023-01-01 00:00:00'
);
r($storyTest->buildStoryForChangeTest(7)) && p('status') && e('active');