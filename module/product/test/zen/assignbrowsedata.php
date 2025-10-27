#!/usr/bin/env php
<?php

/**

title=测试 productZen::assignBrowseData();
timeout=0
cid=0

- 步骤1：正常产品需求浏览数据分配
 - 属性success @1
 - 属性storyType @story
- 步骤2：项目需求浏览数据分配
 - 属性success @1
 - 属性isProjectStory @1
- 步骤3：空需求列表数据分配
 - 属性success @1
 - 属性stories @0
- 步骤4：不同需求类型数据分配
 - 属性success @1
 - 属性storyType @requirement
- 步骤5：不同分支数据分配
 - 属性success @1
 - 属性branch @branch1
 - 属性branchID @branch1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品A,产品B,产品C');
$product->status->range('normal{8},closed{2}');
$product->type->range('normal{6},branch{4}');
$product->PO->range('admin,user1,user2');
$product->gen(10);

$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1-3');
$story->title->range('需求标题1,需求标题2,需求标题3');
$story->type->range('story{15},requirement{5}');
$story->status->range('active{10},draft{5},closed{5}');
$story->gen(20);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目A,项目B,项目C');
$project->status->range('doing{3},wait{2}');
$project->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$productTest = new productTest();

// 5. 测试步骤
r($productTest->assignBrowseDataTest(array(), 'all', 'story', false, (object)array('id' => 1, 'name' => '产品A', 'type' => 'normal'), null, 'all', 'all', '')) && p('success,storyType') && e('1,story'); // 步骤1：正常产品需求浏览数据分配
r($productTest->assignBrowseDataTest(array(), 'all', 'story', true, (object)array('id' => 1, 'name' => '产品A', 'type' => 'normal'), (object)array('id' => 1, 'name' => '项目A'), 'all', 'all', '')) && p('success,isProjectStory') && e('1,1'); // 步骤2：项目需求浏览数据分配
r($productTest->assignBrowseDataTest(array(), 'closed', 'story', false, null, null, '', '', '')) && p('success,stories') && e('1,0'); // 步骤3：空需求列表数据分配
r($productTest->assignBrowseDataTest(array(), 'all', 'requirement', false, (object)array('id' => 2, 'name' => '产品B', 'type' => 'normal'), null, 'all', 'all', '')) && p('success,storyType') && e('1,requirement'); // 步骤4：不同需求类型数据分配
r($productTest->assignBrowseDataTest(array(), 'all', 'story', false, (object)array('id' => 3, 'name' => '产品C', 'type' => 'branch'), null, 'branch1', 'branch1', '')) && p('success,branch,branchID') && e('1,branch1,branch1'); // 步骤5：不同分支数据分配