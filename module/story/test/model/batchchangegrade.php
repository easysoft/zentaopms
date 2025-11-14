#!/usr/bin/env php
<?php

/**

title=测试 storyModel::batchChangeGrade();
timeout=0
cid=18466

- 步骤1：正常修改单个需求等级 @success
- 步骤2：修改多个需求等级为相同值 @success
- 步骤3：修改等级与现有等级相同 @success
- 步骤4：修改不存在的需求等级 @no_stories
- 步骤5：修改需求等级为0 @success
- 步骤6：修改需求类型为requirement的等级 @success
- 步骤7：修改需求等级为负数 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->id->range('1-20');
$story->parent->range('0');
$story->root->range('1-20');
$story->grade->range('1{5},2{5},3{5},4{3},5{2}');
$story->type->range('story{15},requirement{5}');
$story->title->range('用户登录功能,权限管理,数据统计,系统设置');
$story->status->range('active');
$story->deleted->range('0');
$story->version->range('1');
$story->gen(20);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-20');
$storyspec->version->range('1');
$storyspec->title->range('用户登录功能,权限管理,数据统计,系统设置');
$storyspec->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->batchChangeGradeTest(array(1), 3)) && p() && e('success'); // 步骤1：正常修改单个需求等级
r($storyTest->batchChangeGradeTest(array(2, 3), 2)) && p() && e('success'); // 步骤2：修改多个需求等级为相同值
r($storyTest->batchChangeGradeTest(array(4), 4)) && p() && e('success'); // 步骤3：修改等级与现有等级相同
r($storyTest->batchChangeGradeTest(array(999), 1)) && p() && e('no_stories'); // 步骤4：修改不存在的需求等级
r($storyTest->batchChangeGradeTest(array(5), 0)) && p() && e('success'); // 步骤5：修改需求等级为0
r($storyTest->batchChangeGradeTest(array(16), 1, 'requirement')) && p() && e('success'); // 步骤6：修改需求类型为requirement的等级
r($storyTest->batchChangeGradeTest(array(6), -1)) && p() && e('success'); // 步骤7：修改需求等级为负数