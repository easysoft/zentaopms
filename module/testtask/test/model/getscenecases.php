#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::getSceneCases();
timeout=0
cid=19194

- 步骤1：正常情况，返回两个元素数组 @2
- 步骤2：无场景用例，仍返回两个元素数组 @2
- 步骤3：测试空场景的处理，返回两个元素数组 @2
- 步骤4：空runs数组，返回两个元素数组 @2
- 步骤5：不同产品的场景处理 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$scene = zenData('scene');
$scene->id->range('1-10');
$scene->product->range('1{5},2{3},3{2}');
$scene->title->range('登录场景,购物场景,支付场景,用户管理场景,系统设置场景,产品管理场景,订单处理场景,报表查看场景,权限控制场景,数据备份场景');
$scene->parent->range('0{3},1{2},2{2},3{1},4{1},5{1}');
$scene->grade->range('1{3},2{4},3{3}');
$scene->path->range(',1,,2,,3,,1,2,,1,3,,2,3,,2,4,,3,5,,4,6,,5,7,');
$scene->sort->range('1-10');
$scene->deleted->range('0{8},1{2}');
$scene->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的run对象
$run1 = new stdclass();
$run1->scene = 1;
$run1->id = 1;

$run2 = new stdclass();
$run2->scene = 2;
$run2->id = 2;

$run3 = new stdclass();
$run3->scene = '';
$run3->id = 3;

$run4 = new stdclass();
$run4->scene = 0;
$run4->id = 4;

$run5 = new stdclass();
$run5->scene = 6;
$run5->id = 5;

r(count($testtaskTest->getSceneCasesTest(1, array($run1, $run2)))) && p() && e('2'); // 步骤1：正常情况，返回两个元素数组
r(count($testtaskTest->getSceneCasesTest(1, array($run3, $run4)))) && p() && e('2'); // 步骤2：无场景用例，仍返回两个元素数组
r(count($testtaskTest->getSceneCasesTest(1, array($run3)))) && p() && e('2'); // 步骤3：测试空场景的处理，返回两个元素数组
r(count($testtaskTest->getSceneCasesTest(1, array()))) && p() && e('2'); // 步骤4：空runs数组，返回两个元素数组
r(count($testtaskTest->getSceneCasesTest(2, array($run5)))) && p() && e('2'); // 步骤5：不同产品的场景处理