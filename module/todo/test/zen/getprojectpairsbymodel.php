#!/usr/bin/env php
<?php

/**

title=测试 todoZen::getProjectPairsByModel();
timeout=0
cid=19301

- 步骤1：空字符串参数属性processedModel @all
- 步骤2：all模型参数属性processedModel @all
- 步骤3：opportunity模型参数属性processedModel @waterfall
- 步骤4：waterfall模型参数属性processedModel @all
- 步骤5：无效模型参数属性processedModel @all

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->code->range('project1,project2,project3,project4,project5,project6,project7,project8,project9,project10');
$project->model->range('scrum,waterfall,kanban{2},scrum{2},waterfall{3}');
$project->type->range('project{10}');
$project->status->range('wait{3},doing{4},suspended{1},closed{2}');
$project->deleted->range('0{9},1{1}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($todoTest->getProjectPairsByModelTest('')) && p('processedModel') && e('all'); // 步骤1：空字符串参数
r($todoTest->getProjectPairsByModelTest('all')) && p('processedModel') && e('all'); // 步骤2：all模型参数
r($todoTest->getProjectPairsByModelTest('opportunity')) && p('processedModel') && e('waterfall'); // 步骤3：opportunity模型参数
r($todoTest->getProjectPairsByModelTest('waterfall')) && p('processedModel') && e('all'); // 步骤4：waterfall模型参数
r($todoTest->getProjectPairsByModelTest('invalid')) && p('processedModel') && e('all'); // 步骤5：无效模型参数