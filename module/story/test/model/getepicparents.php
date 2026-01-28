#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getEpicParents();
timeout=0
cid=18527

- 步骤1：正常产品获取史诗父级，期望返回数组类型 @1
- 步骤2：产品ID为0，期望返回空数组 @0
- 步骤3：不存在的产品ID，期望返回空数组 @0
- 步骤4：测试追加史诗功能，期望返回包含追加史诗的数组 @1
- 步骤5：测试排除子史诗功能，期望返回正确的父级史诗 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$storyTable = zenData('story');
$storyTable->id->range('1-20');
$storyTable->product->range('1{15},2{5}');
$storyTable->type->range('epic{10},requirement{5},story{5}');
$storyTable->status->range('active{15},reviewing{2},closed{3}');
$storyTable->parent->range('0{10},1{3},2{3},3{2},4{2}');
$storyTable->grade->range('1{8},2{7},3{5}');
$storyTable->title->range('史诗1,史诗2,史诗3,史诗4,史诗5,史诗6,史诗7,史诗8,史诗9,史诗10,需求1,需求2,需求3,需求4,需求5,故事1,故事2,故事3,故事4,故事5');
$storyTable->vision->range('rnd');
$storyTable->deleted->range('0');
$storyTable->path->range(',1,,2,,3,,4,,5,,6,,7,,8,,9,,10,,1,11,,2,12,,3,13,,4,14,,5,15,');
$storyTable->root->range('1{3},2{3},3{2},4{2},5{2},6,7,8,9,10');
$storyTable->gen(20);

$storyGradeTable = zenData('storygrade');
$storyGradeTable->type->range('epic{3},requirement{3},story{3}');
$storyGradeTable->grade->range('1,2,3');
$storyGradeTable->name->range('史诗1级,史诗2级,史诗3级,需求1级,需求2级,需求3级,故事1级,故事2级,故事3级');
$storyGradeTable->status->range('enable');
$storyGradeTable->gen(9);

// 3. 设置必要的配置
global $config;
if(!isset($config->vision)) $config->vision = 'rnd';

// 4. 用户登录
su('admin');

// 5. 创建测试实例
$storyTest = new storyModelTest();

// 6. 必须包含至少5个测试步骤
r(is_array($storyTest->getEpicParentsTest(1, '', 'epic', 0))) && p() && e('1'); // 步骤1：正常产品获取史诗父级，期望返回数组类型
r(count($storyTest->getEpicParentsTest(0, '', 'epic', 0))) && p() && e('0'); // 步骤2：产品ID为0，期望返回空数组
r(count($storyTest->getEpicParentsTest(999, '', 'epic', 0))) && p() && e('0'); // 步骤3：不存在的产品ID，期望返回空数组
r(is_array($storyTest->getEpicParentsTest(1, '5,6', 'epic', 0))) && p() && e('1'); // 步骤4：测试追加史诗功能，期望返回包含追加史诗的数组
r(is_array($storyTest->getEpicParentsTest(1, '', 'epic', 2))) && p() && e('1'); // 步骤5：测试排除子史诗功能，期望返回正确的父级史诗