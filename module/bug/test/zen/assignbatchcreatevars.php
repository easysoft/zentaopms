#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignBatchCreateVars();
timeout=0
cid=0

- 步骤1：正常执行ID情况
 - 属性executionBased @1
 - 属性hasProduct @1
 - 属性productType @normal
- 步骤2：无执行ID情况
 - 属性executionBased @0
 - 属性hasProduct @1
 - 属性productType @normal
- 步骤3：正常产品类型情况
 - 属性hasExecution @1
 - 属性hasBranches @0
- 步骤4：分支产品类型情况
 - 属性hasBranches @1
 - 属性hasBranch @1
 - 属性productType @branch
- 步骤5：包含图片文件情况
 - 属性hasImages @1
 - 属性hasTitles @1
 - 属性imageCount @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal{2},branch{2},platform{1}');
$productTable->status->range('normal');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->model->range('scrum{2},waterfall{2},kanban{1}');
$projectTable->type->range('project');
$projectTable->status->range('wait');
$projectTable->gen(5);

$executionTable = zenData('project');
$executionTable->id->range('101-105');
$executionTable->name->range('执行1,执行2,执行3,执行4,执行5');
$executionTable->type->range('sprint{3},stage{1},kanban{1}');
$executionTable->project->range('1-5');
$executionTable->status->range('wait');
$executionTable->gen(5);

$buildTable = zenData('build');
$buildTable->id->range('1-10');
$buildTable->product->range('1-5');
$buildTable->name->range('版本1,版本2,版本3,版本4,版本5,版本6,版本7,版本8,版本9,版本10');
$buildTable->execution->range('101-105');
$buildTable->deleted->range('0');
$buildTable->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1-5');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$storyTable->status->range('active');
$storyTable->deleted->range('0');
$storyTable->gen(10);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('3-4');
$branchTable->name->range('主干,分支1,分支2,分支3,分支4');
$branchTable->status->range('active');
$branchTable->deleted->range('0');
$branchTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->assignBatchCreateVarsTest(101, (object)array('id' => 1, 'type' => 'normal'), '', array(), array())) && p('executionBased,hasProduct,productType') && e('1,1,normal'); // 步骤1：正常执行ID情况
r($bugTest->assignBatchCreateVarsTest(0, (object)array('id' => 1, 'type' => 'normal'), '', array(), array())) && p('executionBased,hasProduct,productType') && e('0,1,normal'); // 步骤2：无执行ID情况
r($bugTest->assignBatchCreateVarsTest(105, (object)array('id' => 5, 'type' => 'normal'), '', array(), array())) && p('hasExecution,hasBranches') && e('1,0'); // 步骤3：正常产品类型情况
r($bugTest->assignBatchCreateVarsTest(101, (object)array('id' => 3, 'type' => 'branch'), 'all', array(), array())) && p('hasBranches,hasBranch,productType') && e('1,1,branch'); // 步骤4：分支产品类型情况
r($bugTest->assignBatchCreateVarsTest(101, (object)array('id' => 1, 'type' => 'normal'), '', array(), array('test.png' => array('title' => '测试图片')))) && p('hasImages,hasTitles,imageCount') && e('1,1,1'); // 步骤5：包含图片文件情况