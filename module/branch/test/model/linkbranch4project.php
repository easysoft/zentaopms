#!/usr/bin/env php
<?php

/**

title=测试 branchModel::linkBranch4Project();
timeout=0
cid=15333

- 步骤1：测试单个有效产品ID的分支关联 @2
- 步骤2：测试数组产品ID的分支关联 @4
- 步骤3：测试空数组参数 @0
- 步骤4：测试无相关数据的产品ID @0
- 步骤5：测试重复调用的幂等性 @2

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

// 2. zendata数据准备
$projectstoryTable = zenData('projectstory');
$projectstoryTable->project->range('11{5},60{5},61{5},100{5}');
$projectstoryTable->product->range('1{8},2{6},3{6}');
$projectstoryTable->story->range('1-20');
$projectstoryTable->version->range('1');
$projectstoryTable->order->range('1-20');
$projectstoryTable->branch->range('0{4},1{4},2{4},3{4},4{4}');
$projectstoryTable->gen(20);

$bugTable = zenData('bug');
$bugTable->id->range('1-30');
$bugTable->product->range('1{10},2{10},3{10}');
$bugTable->project->range('11{10},60{10},61{10}');
$bugTable->execution->range('100{10},101{10},102{10}');
$bugTable->branch->range('1{10},2{10},3{10}');
$bugTable->title->range('bug1,bug2,bug3');
$bugTable->gen(30);

$caseTable = zenData('case');
$caseTable->id->range('1-30');
$caseTable->product->range('1{10},2{10},3{10}');
$caseTable->project->range('11{10},60{10},61{10}');
$caseTable->execution->range('100{10},101{10},102{10}');
$caseTable->branch->range('1{10},2{10},3{10}');
$caseTable->title->range('case1,case2,case3');
$caseTable->gen(30);

zenData('projectproduct')->loadYaml('projectproduct')->gen(10);
zenData('product')->loadYaml('product')->gen(10);
zenData('user')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$branchTester = new branchTest();

// 5. 执行测试步骤（至少5个）
r($branchTester->linkBranch4ProjectTest(1)) && p() && e('2');            // 步骤1：测试单个有效产品ID的分支关联
r($branchTester->linkBranch4ProjectTest(array(2, 3))) && p() && e('4');  // 步骤2：测试数组产品ID的分支关联
r($branchTester->linkBranch4ProjectTest(array())) && p() && e('0');      // 步骤3：测试空数组参数
r($branchTester->linkBranch4ProjectTest(999)) && p() && e('0');          // 步骤4：测试无相关数据的产品ID
r($branchTester->linkBranch4ProjectTest(1)) && p() && e('2');            // 步骤5：测试重复调用的幂等性