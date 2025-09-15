#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processRecord();
timeout=0
cid=0

- 步骤1：测试任务记录URL包含task模块 @1
- 步骤2：测试kanban项目URL包含index方法 @1
- 步骤3：测试执行记录的额外类型 @sprint
- 步骤4：测试需求记录URL包含storyView方法 @1
- 步骤5：测试文档记录URL包含doc模块 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->project->range('1-3');
$taskTable->name->range('任务{1-10}');
$taskTable->status->range('wait{3},doing{4},done{3}');
$taskTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目{1-10}');
$projectTable->model->range('waterfall{3},scrum{3},kanban{2}');
$projectTable->multiple->range('1{8},0{2}');
$projectTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 强制要求：必须包含至少5个测试步骤
$taskResult = $searchTest->processRecordTest((object)array('objectType' => 'task', 'objectID' => 1), array());
r(strpos($taskResult->url, 'm=task') !== false) && p() && e('1'); // 步骤1：测试任务记录URL包含task模块

$projectResult = $searchTest->processRecordTest((object)array('objectType' => 'project', 'objectID' => 3), array('project' => array(3 => (object)array('model' => 'kanban'))));
r(strpos($projectResult->url, 'f=index') !== false) && p() && e('1'); // 步骤2：测试kanban项目URL包含index方法

$executionResult = $searchTest->processRecordTest((object)array('objectType' => 'execution', 'objectID' => 2), array('execution' => array(2 => (object)array('type' => 'sprint', 'project' => 1))));
r($executionResult->extraType) && p() && e('sprint'); // 步骤3：测试执行记录的额外类型

$storyResult = $searchTest->processRecordTest((object)array('objectType' => 'story', 'objectID' => 1), array('story' => array(1 => (object)array('type' => 'story', 'lib' => 0))));
r(strpos($storyResult->url, 'f=storyView') !== false) && p() && e('1'); // 步骤4：测试需求记录URL包含storyView方法

$docResult = $searchTest->processRecordTest((object)array('objectType' => 'doc', 'objectID' => 7), array('doc' => array(7 => (object)array('assetLib' => 0))));
r(strpos($docResult->url, 'm=doc') !== false) && p() && e('1'); // 步骤5：测试文档记录URL包含doc模块