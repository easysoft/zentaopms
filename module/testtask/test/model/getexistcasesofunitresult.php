#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::getExistCasesOfUnitResult();
timeout=0
cid=19173

- 步骤1：正常情况查找已存在用例
 - 属性UnitCase1 @1
 - 属性UnitCase2 @2
- 步骤2：空数组输入 @0
- 步骤3：不存在的用例标题 @0
- 步骤4：部分标题存在属性UnitCase1 @1
- 步骤5：套件ID筛选，返回找到的用例属性UnitCase3 @3
- 步骤6：产品ID筛选，但类型不匹配 @0
- 步骤7：auto类型筛选测试
 - 属性UnitCase1 @1
 - 属性UnitCase3 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('case');
$table->id->range('1-20');
$table->product->range('1{8},2{6},3{6}');
$table->module->range('1-10{2}');
$table->title->range('UnitCase1,UnitCase2,UnitCase3,AutoCase1,AutoCase2,ManualCase1,ManualCase2,DeletedCase,TestCase1,TestCase2,TestCase3,TestCase4,TestCase5,TestCase6,TestCase7,TestCase8,TestCase9,TestCase10,TestCase11,TestCase12');
$table->type->range('unit{8},feature{6},auto{6}');
$table->auto->range('unit{8},no{6},auto{6}');
$table->pri->range('1{5},2{5},3{5},4{5}');
$table->status->range('normal{18},blocked{2}');
$table->stage->range('unittest{8},feature{6},system{6}');
$table->story->range('1-10{2}');
$table->precondition->range('前置条件描述{20}');
$table->keywords->range('关键词1,关键词2,关键词3');
$table->openedBy->range('admin{8},user1{6},user2{6}');
$table->openedDate->range('`2023-01-01 00:00:00`,`2023-02-01 00:00:00`,`2023-03-01 00:00:00`,`2023-04-01 00:00:00`,`2023-05-01 00:00:00`');
$table->lastEditedBy->range('admin{8},user1{6},user2{6}');
$table->lastEditedDate->range('`2023-01-01 00:00:00`,`2023-02-01 00:00:00`,`2023-03-01 00:00:00`,`2023-04-01 00:00:00`,`2023-05-01 00:00:00`');
$table->version->range('1{20}');
$table->deleted->range('0{18},1{2}');
$table->gen(20);

// 套件用例关联表数据
$suiteTable = zenData('suitecase');
$suiteTable->suite->range('1{5},2{5},3{5}');
$suiteTable->product->range('1{8},2{4},3{3}');
$suiteTable->case->range('1-15');
$suiteTable->version->range('1{15}');
$suiteTable->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testtaskTest->getExistCasesOfUnitResultTest(['UnitCase1', 'UnitCase2'], 1, 1, 'unit')) && p('UnitCase1,UnitCase2') && e('1,2'); // 步骤1：正常情况查找已存在用例
r($testtaskTest->getExistCasesOfUnitResultTest([], 1, 1, 'unit')) && p() && e('0'); // 步骤2：空数组输入
r($testtaskTest->getExistCasesOfUnitResultTest(['NonExistCase'], 1, 1, 'unit')) && p() && e('0'); // 步骤3：不存在的用例标题
r($testtaskTest->getExistCasesOfUnitResultTest(['UnitCase1', 'NonExistCase'], 1, 1, 'unit')) && p('UnitCase1') && e('1'); // 步骤4：部分标题存在
r($testtaskTest->getExistCasesOfUnitResultTest(['UnitCase3'], 2, 1, 'unit')) && p('UnitCase3') && e('3'); // 步骤5：套件ID筛选，返回找到的用例
r($testtaskTest->getExistCasesOfUnitResultTest(['ManualCase1'], 0, 2, 'no')) && p() && e('0'); // 步骤6：产品ID筛选，但类型不匹配
r($testtaskTest->getExistCasesOfUnitResultTest(['UnitCase1', 'UnitCase3'], 0, 1, 'unit')) && p('UnitCase1,UnitCase3') && e('1,3'); // 步骤7：auto类型筛选测试