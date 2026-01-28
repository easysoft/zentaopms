#!/usr/bin/env php
<?php

/**

title=测试 storyModel::checkGrade();
timeout=0
cid=18481

- 正常情况返回true @1
- 层级不变返回true @1
- 批量模式正常返回true @1
- 在系统范围内返回true @1
- 超出系统限制返回错误信息属性grade @系统检测该需求下子需求的最大层级为5，同步修改后为6，超出系统设置的层级范围，无法修改。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->type->range('story');
$storyTable->grade->range('1{3},2{4},3{3}');
$storyTable->parent->range('0{3},1{2},2{2},1{3}');
$storyTable->path->range(',1,{3},1,2,{2},1,3,{2},1,4,{3}');
$storyTable->deleted->range('0');
$storyTable->gen(10);

$storyGradeTable = zenData('storygrade');
$storyGradeTable->type->range('story');
$storyGradeTable->grade->range('1,2,3,4,5');
$storyGradeTable->name->range('SR,子,孙,曾,玄');
$storyGradeTable->status->range('enable');
$storyGradeTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常层级更新 - 父需求从层级1更新到层级2，子需求最大层级是2，系统最大层级是5
$story = new stdclass();
$story->grade = 2;
$oldStory = new stdclass();
$oldStory->id = 1;
$oldStory->grade = 1;
$oldStory->type = 'story';
r($storyTest->checkGradeTest($story, $oldStory, 'single')) && p() && e('1'); // 正常情况返回true

// 步骤2：层级不变的情况 - 不应该有错误
$story2 = new stdclass();
$story2->grade = 1;
$oldStory2 = new stdclass();
$oldStory2->id = 1;
$oldStory2->grade = 1;
$oldStory2->type = 'story';
r($storyTest->checkGradeTest($story2, $oldStory2, 'single')) && p() && e('1'); // 层级不变返回true

// 步骤3：批量模式下的正常情况
$story3 = new stdclass();
$story3->grade = 2;
$oldStory3 = new stdclass();
$oldStory3->id = 3;
$oldStory3->grade = 1;
$oldStory3->type = 'story';
r($storyTest->checkGradeTest($story3, $oldStory3, 'batch')) && p() && e('1'); // 批量模式正常返回true

// 步骤4：层级大幅度增加但仍在范围内
$story4 = new stdclass();
$story4->grade = 3;
$oldStory4 = new stdclass();
$oldStory4->id = 3;
$oldStory4->grade = 1;
$oldStory4->type = 'story';
r($storyTest->checkGradeTest($story4, $oldStory4, 'single')) && p() && e('1'); // 在系统范围内返回true

// 步骤5：层级超出系统限制的情况 - 假设子需求最大层级3，父需求从1改到4，子需求会变成6，超出系统最大层级5
$story5 = new stdclass();
$story5->grade = 4;
$oldStory5 = new stdclass();
$oldStory5->id = 1;
$oldStory5->grade = 1;
$oldStory5->type = 'story';
r($storyTest->checkGradeTest($story5, $oldStory5, 'single')) && p('grade') && e('系统检测该需求下子需求的最大层级为5，同步修改后为6，超出系统设置的层级范围，无法修改。'); // 超出系统限制返回错误信息