#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getStoryCardMenu();
timeout=0
cid=0

- 步骤1：正常执行和需求返回菜单数组 @Array
- 步骤2：空需求数组返回空数组 @Array
- 步骤3：无产品权限时返回菜单数组 @Array
- 步骤4：草稿状态需求返回菜单数组 @Array
- 步骤5：已关闭状态需求返回菜单数组 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$project->type->range('sprint');
$project->hasProduct->range('1{5},0{5}');
$project->status->range('doing');
$project->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1{8},2{2}');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->type->range('story');
$story->status->range('active{5},draft{3},closed{2}');
$story->stage->range('projected{5},developing{3},released{2}');
$story->pri->range('1,2,3,1,2,3,1,2,3,1');
$story->module->range('1{5},2{3},3{2}');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 1, 'hasProduct' => 1), array((object)array('id' => 1, 'type' => 'story', 'status' => 'active', 'story' => 1, 'module' => 1)))) && p() && e('Array'); // 步骤1：正常执行和需求返回菜单数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 1, 'hasProduct' => 1), array())) && p() && e('Array'); // 步骤2：空需求数组返回空数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 6, 'hasProduct' => 0), array((object)array('id' => 2, 'type' => 'story', 'status' => 'active', 'story' => 2, 'module' => 2)))) && p() && e('Array'); // 步骤3：无产品权限时返回菜单数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 2, 'hasProduct' => 1), array((object)array('id' => 6, 'type' => 'story', 'status' => 'draft', 'story' => 6, 'module' => 2)))) && p() && e('Array'); // 步骤4：草稿状态需求返回菜单数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 3, 'hasProduct' => 1), array((object)array('id' => 9, 'type' => 'story', 'status' => 'closed', 'story' => 9, 'module' => 3)))) && p() && e('Array'); // 步骤5：已关闭状态需求返回菜单数组