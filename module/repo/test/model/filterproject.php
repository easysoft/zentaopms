#!/usr/bin/env php
<?php

/**

title=测试 repoModel::filterProject();
timeout=0
cid=18043

- 步骤1：正常输入，产品1,2关联项目+直接链接项目11,12 @5
- 步骤2：仅产品ID列表，产品3,4关联的项目 @2
- 步骤3：仅项目ID列表，直接链接项目15,16 @2
- 步骤4：空数组输入 @0
- 步骤5：不存在的ID @0
- 步骤6：部分存在的ID，产品1关联项目 @2
- 步骤7：重复ID去重测试 @5

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$productTable->status->range('normal{8},closed{2}');
$productTable->deleted->range('0{9},1{1}');
$productTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-20');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10,项目11,项目12,项目13,项目14,项目15,项目16,项目17,项目18,项目19,项目20');
$projectTable->type->range('project{18},kanban{2}');
$projectTable->status->range('wait{5},doing{10},suspended{3},closed{2}');
$projectTable->deleted->range('0{18},1{2}');
$projectTable->gen(20);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('1,2,3,4,5');
$projectProductTable->product->range('1,2,1,3,4');
$projectProductTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoTest();

// 5. 执行测试步骤
r($repoTest->filterProjectTest(array(1, 2), array(11, 12))) && p() && e('5'); // 步骤1：正常输入，产品1,2关联项目+直接链接项目11,12
r($repoTest->filterProjectTest(array(3, 4), array())) && p() && e('2'); // 步骤2：仅产品ID列表，产品3,4关联的项目
r($repoTest->filterProjectTest(array(), array(15, 16))) && p() && e('2'); // 步骤3：仅项目ID列表，直接链接项目15,16
r($repoTest->filterProjectTest(array(), array())) && p() && e('0'); // 步骤4：空数组输入
r($repoTest->filterProjectTest(array(999), array(999))) && p() && e('0'); // 步骤5：不存在的ID
r($repoTest->filterProjectTest(array(1), array(999))) && p() && e('2'); // 步骤6：部分存在的ID，产品1关联项目
r($repoTest->filterProjectTest(array(1, 1, 2), array(11, 11, 12))) && p() && e('5'); // 步骤7：重复ID去重测试