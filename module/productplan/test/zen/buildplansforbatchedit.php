#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildPlansForBatchEdit();
timeout=0
cid=0

- 步骤1：正常情况第1条的title属性 @修改后的计划1
- 步骤2：空日期处理第3条的begin属性 @2024-03-01
- 步骤3：日期验证错误 @1
- 步骤4：future处理第5条的begin属性 @2030-01-01
- 步骤5：分支处理第6条的branch属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('productplan');
$table->id->range('1-10');
$table->product->range('1{5},2{3},3{2}');
$table->branch->range('0{8},1{2}');
$table->parent->range('0{8},-1{1},1{1}');
$table->title->range('计划1,计划2,计划3,计划4,计划5,父计划1,父计划2,子计划1,子计划2,独立计划');
$table->status->range('wait{4},doing{3},done{2},closed{1}');
$table->begin->range('2024-01-01,2024-02-01,2024-03-01,2024-04-01,2024-05-01,2030-01-01{3},2024-06-01,2024-07-01');
$table->end->range('2024-01-31,2024-02-28,2024-03-31,2024-04-30,2024-05-31,2030-01-01{3},2024-06-30,2024-07-31');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productplanTest = new productplanZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：模拟正常的批量编辑数据验证
$_POST = array(
    'title' => array(1 => '修改后的计划1', 2 => '修改后的计划2'),
    'status' => array(1 => 'doing', 2 => 'done'),
    'begin' => array(1 => '2024-02-01', 2 => '2024-03-01'),
    'end' => array(1 => '2024-02-28', 2 => '2024-03-31'),
    'branch' => array(1 => '0', 2 => '0')
);
r($productplanTest->buildPlansForBatchEditTest()) && p('1:title') && e('修改后的计划1'); // 步骤1：正常情况

// 测试步骤2：空日期处理
$_POST = array(
    'title' => array(3 => '计划3'),
    'status' => array(3 => 'wait'),
    'begin' => array(3 => ''),
    'end' => array(3 => ''),
    'branch' => array(3 => '0')
);
r($productplanTest->buildPlansForBatchEditTest()) && p('3:begin') && e('2024-03-01'); // 步骤2：空日期处理

// 测试步骤3：日期验证错误  
$_POST = array(
    'title' => array(4 => '计划4'),
    'status' => array(4 => 'wait'),
    'begin' => array(4 => '2024-05-01'),
    'end' => array(4 => '2024-04-01'),
    'branch' => array(4 => '0')
);
$result3 = $productplanTest->buildPlansForBatchEditTest();
r(is_string($result3)) && p() && e('1'); // 步骤3：日期验证错误

// 测试步骤4：future标记处理
$_POST = array(
    'title' => array(5 => '计划5'),
    'status' => array(5 => 'wait'),
    'begin' => array(5 => '2024-07-01'),
    'end' => array(5 => '2024-07-31'),
    'branch' => array(5 => '0'),
    'future' => array(5 => '1')
);
r($productplanTest->buildPlansForBatchEditTest()) && p('5:begin') && e('2030-01-01'); // 步骤4：future处理

// 测试步骤5：分支处理 
$_POST = array(
    'title' => array(6 => '计划6'),
    'status' => array(6 => 'wait'),
    'begin' => array(6 => '2024-08-01'),
    'end' => array(6 => '2024-08-31'),
    'branch' => array(6 => '')
);
r($productplanTest->buildPlansForBatchEditTest()) && p('6:branch') && e('0'); // 步骤5：分支处理