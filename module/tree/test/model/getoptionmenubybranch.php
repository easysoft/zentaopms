#!/usr/bin/env php
<?php

/**

title=测试 treeModel::getOptionMenuByBranch();
timeout=0
cid=19373

- 步骤1：正常story类型根节点 @/
- 步骤2：bug类型根节点 @/
- 步骤3：case类型根节点 @/
- 步骤4：指定branch根节点 @/
- 步骤5：不存在rootID返回根节点 @/
- 步骤6：特定grade根节点 @/
- 步骤7：自定义分隔符根节点 @/

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

// 2. zendata数据准备
$table = zenData('module');
$table->id->range('1-20');
$table->root->range('1-3');
$table->branch->range('0,1,2');
$table->name->range('前端,后端,移动端,UI设计,需求分析,测试,运维,文档,安全,性能{2}');
$table->parent->range('0{5},1{5},2{5},3{5}');
$table->path->range('1,2,3,4,5,1+11,1+12,1+13,1+14,1+15,2+16,2+17,2+18,2+19,2+20');
$table->grade->range('1{5},2{10},3{5}');
$table->order->range('1-20');
$table->type->range('story{10},bug{5},case{5}');
$table->from->range('0');
$table->owner->range('admin,user1,user2');
$table->collector->range('');
$table->short->range('FE,BE,MB,UI,REQ,QA,OPS,DOC,SEC,PERF{2}');
$table->deleted->range('0{18},1{2}');
$table->gen(20);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{2},branch{3}');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(5);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('3,4,5');
$branchTable->name->range('主分支,开发分支,测试分支,发布分支,修复分支');
$branchTable->status->range('active');
$branchTable->deleted->range('0');
$branchTable->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$treeTest = new treeTest();

// 5. 测试步骤
r($treeTest->getOptionMenuByBranchTest(1, 'story', 0, 'all', 'nodeleted', 'all', '/')) && p('0') && e('/'); // 步骤1：正常story类型根节点
r($treeTest->getOptionMenuByBranchTest(2, 'bug', 0, 'all', 'nodeleted', 'all', '/')) && p('0') && e('/'); // 步骤2：bug类型根节点  
r($treeTest->getOptionMenuByBranchTest(3, 'case', 0, 'all', 'nodeleted', 'all', '/')) && p('0') && e('/'); // 步骤3：case类型根节点
r($treeTest->getOptionMenuByBranchTest(3, 'story', 0, '1', 'nodeleted', 'all', '/')) && p('0') && e('/'); // 步骤4：指定branch根节点
r($treeTest->getOptionMenuByBranchTest(999, 'story', 0, 'all', 'nodeleted', 'all', '/')) && p('0') && e('/'); // 步骤5：不存在rootID返回根节点
r($treeTest->getOptionMenuByBranchTest(1, 'story', 0, 'all', 'nodeleted', '2', '/')) && p('0') && e('/'); // 步骤6：特定grade根节点
r($treeTest->getOptionMenuByBranchTest(1, 'story', 0, 'all', 'nodeleted', 'all', '|')) && p('0') && e('/'); // 步骤7：自定义分隔符根节点