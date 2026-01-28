#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::getExistSuitesOfUnitResult();
timeout=0
cid=19174

- 步骤1：正常情况
 - 属性UnitSuite1 @1
 - 属性UnitSuite2 @2
- 步骤2：空数组输入 @0
- 步骤3：不存在的套件名称 @0
- 步骤4：部分存在属性UnitSuite1 @1
- 步骤5：产品ID筛选属性ManualSuite1 @6
- 步骤6：类型筛选属性TestSuite2 @9
- 步骤7：已删除的套件 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('testsuite');
$table->id->range('1-15');
$table->project->range('1{5},2{5},3{5}');
$table->product->range('1{5},2{5},3{5}');
$table->name->range('UnitSuite1,UnitSuite2,UnitSuite3,AutoSuite1,AutoSuite2,ManualSuite1,DeletedSuite,TestSuite1,TestSuite2,TestSuite3,TestSuite4,TestSuite5,TestSuite6,TestSuite7,TestSuite8');
$table->desc->range('单元测试套件描述{15}');
$table->type->range('unit{8},auto{4},manual{3}');
$table->order->range('1-15');
$table->addedBy->range('admin{8},user1{4},user2{3}');
$table->addedDate->range('`2023-01-01 00:00:00`,`2023-02-01 00:00:00`,`2023-03-01 00:00:00`,`2023-04-01 00:00:00`,`2023-05-01 00:00:00`');
$table->lastEditedBy->range('admin{8},user1{4},user2{3}');
$table->lastEditedDate->range('`2023-01-01 00:00:00`,`2023-02-01 00:00:00`,`2023-03-01 00:00:00`,`2023-04-01 00:00:00`,`2023-05-01 00:00:00`');
$table->deleted->range('0{12},1{3}');
$table->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskModelTest();

// 5. 执行测试步骤（至少5个）
r($testtaskTest->getExistSuitesOfUnitResultTest(['UnitSuite1', 'UnitSuite2'], 1, 'unit')) && p('UnitSuite1,UnitSuite2') && e('1,2'); // 步骤1：正常情况
r($testtaskTest->getExistSuitesOfUnitResultTest([], 1, 'unit')) && p() && e('0'); // 步骤2：空数组输入
r($testtaskTest->getExistSuitesOfUnitResultTest(['NonExistSuite'], 1, 'unit')) && p() && e('0'); // 步骤3：不存在的套件名称
r($testtaskTest->getExistSuitesOfUnitResultTest(['UnitSuite1', 'NonExistSuite'], 1, 'unit')) && p('UnitSuite1') && e('1'); // 步骤4：部分存在
r($testtaskTest->getExistSuitesOfUnitResultTest(['ManualSuite1'], 2, 'unit')) && p('ManualSuite1') && e('6'); // 步骤5：产品ID筛选
r($testtaskTest->getExistSuitesOfUnitResultTest(['TestSuite2'], 2, 'auto')) && p('TestSuite2') && e('9'); // 步骤6：类型筛选
r($testtaskTest->getExistSuitesOfUnitResultTest(['TestSuite6'], 3, 'manual')) && p() && e('0'); // 步骤7：已删除的套件