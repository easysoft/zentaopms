#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildBugSearchForm();
timeout=0
cid=18123

- 步骤1：正常情况属性result @success
- 步骤2：带分支产品属性branchRemoved @0
- 步骤3：无分支产品属性branchRemoved @1
- 步骤4：空产品列表
 - 属性productCount @0
 - 属性moduleCount @0
- 步骤5：多产品多模块
 - 属性productCount @3
 - 属性moduleCount @5
 - 属性planCount @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->status->range('normal');
$table->gen(5);

$branch = zenData('branch');
$branch->id->range('1-3');
$branch->product->range('4-5:1');
$branch->name->range('分支1,分支2,分支3');
$branch->gen(3);

$build = zenData('build');
$build->id->range('1-5');
$build->product->range('1-5:1');
$build->name->range('版本1,版本2,版本3,版本4,版本5');
$build->gen(5);

$productplan = zenData('productplan');
$productplan->id->range('1-3');
$productplan->product->range('1-3:1');
$productplan->title->range('计划1,计划2,计划3');
$productplan->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->buildBugSearchFormTest(1, 'abc123', 'bysearch', 1, array('1' => (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'), '2' => (object)array('id' => 2, 'name' => '产品2', 'type' => 'normal')), array('1' => '模块1', '2' => '模块2'))) && p('result') && e('success'); // 步骤1：正常情况
r($repoTest->buildBugSearchFormTest(2, 'def456', 'bysearch', 2, array('4' => (object)array('id' => 4, 'name' => '产品4', 'type' => 'branch'), '5' => (object)array('id' => 5, 'name' => '产品5', 'type' => 'branch')), array('3' => '模块3', '4' => '模块4'))) && p('branchRemoved') && e('0'); // 步骤2：带分支产品
r($repoTest->buildBugSearchFormTest(3, 'ghi789', 'all', 3, array('1' => (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'), '3' => (object)array('id' => 3, 'name' => '产品3', 'type' => 'normal')), array('5' => '模块5'))) && p('branchRemoved') && e('1'); // 步骤3：无分支产品
r($repoTest->buildBugSearchFormTest(4, 'jkl012', 'bysearch', 4, array(), array())) && p('productCount,moduleCount') && e('0,0'); // 步骤4：空产品列表
r($repoTest->buildBugSearchFormTest(5, 'mno345', 'all', 5, array('1' => (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal'), '2' => (object)array('id' => 2, 'name' => '产品2', 'type' => 'normal'), '4' => (object)array('id' => 4, 'name' => '产品4', 'type' => 'branch')), array('1' => '模块1', '2' => '模块2', '3' => '模块3', '4' => '模块4', '5' => '模块5'))) && p('productCount,moduleCount,planCount') && e('3,5,3'); // 步骤5：多产品多模块