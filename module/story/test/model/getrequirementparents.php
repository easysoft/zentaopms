#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getRequirementParents();
timeout=0
cid=18551

- 步骤1：正常产品获取父需求，当前数据预期返回0 @0
- 步骤2：产品ID为0，期望返回0个结果 @0
- 步骤3：不存在的产品ID，期望返回0个结果 @0
- 步骤4：测试追加故事功能，期望返回数组 @1
- 步骤5：测试排除子需求功能，期望返回数组 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$storyTable = zenData('story');
$storyTable->id->range('1-15');
$storyTable->product->range('1{10},2{5}');
$storyTable->type->range('epic{3},requirement{7},story{5}');
$storyTable->status->range('active{12},reviewing{2},closed{1}');
$storyTable->parent->range('0{8},1{3},2{2},3{2}');
$storyTable->grade->range('1{3},2{7},3{5}');
$storyTable->title->range('史诗需求1,史诗需求2,史诗需求3,用户需求1,用户需求2,用户需求3,用户需求4,用户需求5,用户需求6,用户需求7,软件需求1,软件需求2,软件需求3,软件需求4,软件需求5');
$storyTable->vision->range('rnd');
$storyTable->deleted->range('0');
$storyTable->gen(15);

$storyGradeTable = zenData('storygrade');
$storyGradeTable->type->range('epic{3},requirement{3},story{3}');
$storyGradeTable->grade->range('1,2,3');
$storyGradeTable->name->range('史诗1级,史诗2级,史诗3级,需求1级,需求2级,需求3级,故事1级,故事2级,故事3级');
$storyGradeTable->status->range('enable');
$storyGradeTable->gen(9);

// 3. 设置必要的配置
global $config;
if(!isset($config->epic)) $config->epic = new stdclass();
$config->epic->gradeRule = 'stepwise';
if(!isset($config->enableER)) $config->enableER = true;
if(!isset($config->vision)) $config->vision = 'rnd';

// 4. 用户登录
su('admin');

// 5. 创建测试实例
$storyTest = new storyModelTest();

// 6. 必须包含至少5个测试步骤
r(count($storyTest->getRequirementParentsTest(1, '', 'requirement', 0))) && p() && e('0'); // 步骤1：正常产品获取父需求，当前数据预期返回0
r(count($storyTest->getRequirementParentsTest(0, '', 'requirement', 0))) && p() && e('0'); // 步骤2：产品ID为0，期望返回0个结果
r(count($storyTest->getRequirementParentsTest(999, '', 'requirement', 0))) && p() && e('0'); // 步骤3：不存在的产品ID，期望返回0个结果
r(is_array($storyTest->getRequirementParentsTest(1, '5,6', 'requirement', 0))) && p() && e('1'); // 步骤4：测试追加故事功能，期望返回数组
r(is_array($storyTest->getRequirementParentsTest(1, '', 'requirement', 2))) && p() && e('1'); // 步骤5：测试排除子需求功能，期望返回数组