#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshStoryCards();
timeout=0
cid=16991

- 步骤1:测试处于开发中(developing)状态的需求卡片刷新
 - 属性developing @
- 步骤2:测试处于已测试(tested)状态的需求卡片刷新
 - 属性tested @
- 步骤3:测试处于已发布(released)状态的需求卡片刷新
 - 属性released @
- 步骤4:测试处于等待和已立项(wait/projected)状态的需求添加到backlog列
 - 属性backlog @
- 步骤5:测试包含已有卡片的刷新
 - 属性developing @

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备(根据需要配置)
$project = zenData('project');
$project->id->range('101');
$project->type->range('project');
$project->hasProduct->range('1');
$project->gen(1);

$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1');
$story->type->range('story');
$story->isParent->range('0');
$story->stage->range('wait,projected,designing,designed,developing,developed,testing,tested,verified,rejected,delivering,delivered,released,closed,wait,projected,developing,tested,released,closed');
$story->status->range('active{18},closed{2}');
$story->deleted->range('0{18},1{2}');
$story->gen(20);

$projectStory = zenData('projectstory');
$projectStory->project->range('101');
$projectStory->product->range('1');
$projectStory->story->range('1-20');
$projectStory->version->range('1');
$projectStory->gen(20);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$kanbanTest = new kanbanTaoTest();

// 5. 强制要求:必须包含至少5个测试步骤
$cardPairs = array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'develop' => '', 'developing' => '', 'developed' => '', 'test' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'rejected' => '', 'pending' => '', 'released' => '', 'closed' => '');
r($kanbanTest->refreshStoryCardsTest($cardPairs, 101, '')) && p('developing') && e(',17,5,'); // 步骤1:测试处于开发中(developing)状态的需求卡片刷新
r($kanbanTest->refreshStoryCardsTest($cardPairs, 101, '')) && p('tested') && e(',18,8,'); // 步骤2:测试处于已测试(tested)状态的需求卡片刷新
r($kanbanTest->refreshStoryCardsTest($cardPairs, 101, '')) && p('released') && e(',13,'); // 步骤3:测试处于已发布(released)状态的需求卡片刷新
r($kanbanTest->refreshStoryCardsTest($cardPairs, 101, '')) && p('backlog') && e(',16,15,2,1,'); // 步骤4:测试处于等待和已立项(wait/projected)状态的需求添加到backlog列
r($kanbanTest->refreshStoryCardsTest(array('backlog' => '', 'ready' => '', 'designing' => '', 'designed' => '', 'develop' => '', 'developing' => ',100,', 'developed' => '', 'test' => '', 'testing' => '', 'tested' => '', 'verified' => '', 'rejected' => '', 'pending' => '', 'released' => '', 'closed' => ''), 101, '')) && p('developing') && e(',17,5,100,'); // 步骤5:测试包含已有卡片的刷新