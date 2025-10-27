#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getStoriesForCreate();
timeout=0
cid=0

- 步骤1：有执行ID时获取需求数量 @4
- 步骤2：有项目ID但无执行ID时获取需求数量 @4
- 步骤3：有模块ID时获取需求数量 @2
- 步骤4：无项目执行模块ID时获取需求数量 @2
- 步骤5：备用获取需求数量 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1{10},2{10}');
$story->branch->range('0{15},1{5}');
$story->module->range('1001{5},1002{5},0{10}');
$story->title->range('需求标题%s');
$story->type->range('story');
$story->status->range('active');
$story->stage->range('wait{8},planned{8},projected{4}');
$story->version->range('1');
$story->gen(20);

$projectstory = zenData('projectstory'); 
$projectstory->project->range('11{5},12{5}');
$projectstory->product->range('1');
$projectstory->story->range('1-10');
$projectstory->version->range('1');
$projectstory->gen(10);

$module = zenData('module');
$module->id->range('1001-1002');
$module->root->range('1');
$module->type->range('story');
$module->path->range(',1001,{1},,1001,1002,{1}');
$module->grade->range('1{1},2{1}');
$module->parent->range('1001{1},1001{1}');
$module->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($bugTest->getStoriesForCreateTest((object)array('productID' => 1, 'branch' => '0', 'moduleID' => 0, 'projectID' => 0, 'executionID' => 11))) && p() && e('4'); // 步骤1：有执行ID时获取需求数量
r($bugTest->getStoriesForCreateTest((object)array('productID' => 1, 'branch' => '0', 'moduleID' => 0, 'projectID' => 11, 'executionID' => 0))) && p() && e('4'); // 步骤2：有项目ID但无执行ID时获取需求数量
r($bugTest->getStoriesForCreateTest((object)array('productID' => 1, 'branch' => '0', 'moduleID' => 1001, 'projectID' => 0, 'executionID' => 0))) && p() && e('2'); // 步骤3：有模块ID时获取需求数量  
r($bugTest->getStoriesForCreateTest((object)array('productID' => 1, 'branch' => '0', 'moduleID' => 0, 'projectID' => 0, 'executionID' => 0))) && p() && e('2'); // 步骤4：无项目执行模块ID时获取需求数量
r($bugTest->getStoriesForCreateTest((object)array('productID' => 2, 'branch' => '0', 'moduleID' => 1003, 'projectID' => 0, 'executionID' => 0))) && p() && e('2'); // 步骤5：备用获取需求数量