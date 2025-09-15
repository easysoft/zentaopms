#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getStoryCardMenu();
timeout=0
cid=0

- 步骤1：正常情况返回菜单数组 @Array
- 步骤2：空执行对象返回空数组 @Array
- 步骤3：空需求数组返回空数组 @Array
- 步骤4：无产品权限情况返回菜单数组 @Array
- 步骤5：草稿状态需求返回菜单数组 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('执行1,执行2,执行3,执行4,执行5');
$project->type->range('sprint');
$project->hasProduct->range('1{3},0{2}');
$project->status->range('doing');
$project->gen(5);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->type->range('story');
$story->status->range('active{3},draft{2}');
$story->stage->range('projected');
$story->pri->range('3');
$story->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 1, 'hasProduct' => 1), array((object)array('id' => 1, 'type' => 'story', 'status' => 'active', 'story' => 1, 'module' => 1)))) && p() && e('Array'); // 步骤1：正常情况返回菜单数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 0, 'hasProduct' => 0), array())) && p() && e('Array'); // 步骤2：空执行对象返回空数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 1, 'hasProduct' => 1), array())) && p() && e('Array'); // 步骤3：空需求数组返回空数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 4, 'hasProduct' => 0), array((object)array('id' => 2, 'type' => 'story', 'status' => 'active', 'story' => 2, 'module' => 2)))) && p() && e('Array'); // 步骤4：无产品权限情况返回菜单数组
r($kanbanTest->getStoryCardMenuTest((object)array('id' => 2, 'hasProduct' => 1), array((object)array('id' => 4, 'type' => 'story', 'status' => 'draft', 'story' => 4, 'module' => 1)))) && p() && e('Array'); // 步骤5：草稿状态需求返回菜单数组