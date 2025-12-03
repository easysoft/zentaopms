#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshERURCards();
timeout=0
cid=16990

- 步骤1:测试用需(epic)类型需求卡片刷新,处于已计划状态
 - 属性planned @
- 步骤2:测试业需(requirement)类型需求卡片刷新,处于已立项状态
 - 属性projected @
- 步骤3:测试用需(epic)类型需求,处于已立项状态
 - 属性projected @
- 步骤4:测试需求已在列中的情况
 - 属性planned @
- 步骤5:测试包含已有卡片的刷新
 - 属性planned @

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备(根据需要配置)
$story = zenData('story');
$story->id->range('1-20');
$story->type->range('epic{5},requirement{5},story{10}');
$story->isParent->range('0,1{4},0{15}');
$story->stage->range('planned,projected,developing,delivering,delivered,closed,wait,planned,projected,developing,delivered,closed,planned,projected,developing,delivering,delivered,closed,wait,planned');
$story->status->range('active{15},closed{5}');
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
$cardPairs = array('wait' => '', 'planned' => '', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => '');
r($kanbanTest->refreshERURCardsTest($cardPairs, 101, '', 'epic')) && p('planned') && e(',1,'); // 步骤1:测试用需(epic)类型需求卡片刷新,处于已计划状态
r($kanbanTest->refreshERURCardsTest($cardPairs, 101, '', 'requirement')) && p('projected') && e(',9,'); // 步骤2:测试业需(requirement)类型需求卡片刷新,处于已立项状态
r($kanbanTest->refreshERURCardsTest($cardPairs, 101, '', 'epic')) && p('projected') && e(',2,'); // 步骤3:测试用需(epic)类型需求,处于已立项状态
r($kanbanTest->refreshERURCardsTest(array('wait' => '', 'planned' => ',1,', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => ''), 101, '', 'epic')) && p('planned') && e(',1,'); // 步骤4:测试需求已在列中的情况
r($kanbanTest->refreshERURCardsTest(array('wait' => '', 'planned' => ',100,', 'projected' => '', 'developing' => '', 'delivering' => '', 'delivered' => '', 'closed' => ''), 101, '', 'epic')) && p('planned') && e(',1,100,'); // 步骤5:测试包含已有卡片的刷新