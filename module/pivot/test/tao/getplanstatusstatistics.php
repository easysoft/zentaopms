#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getPlanStatusStatistics();
timeout=0
cid=0

步骤1：正常情况统计已计划需求状态 >> success
步骤2：统计未计划需求状态 >> success
步骤3：测试多个计划的需求状态统计 >> success
步骤4：测试空数据情况 >> success
步骤5：测试需求属于多个计划的情况 >> success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品A{3},产品B{3},产品C{4}');
$product->status->range('normal{8},closed{2}');
$product->deleted->range('0{10}');
$product->shadow->range('0{10}');
$product->gen(10);

$productplan = zenData('productplan');
$productplan->id->range('1-15');
$productplan->product->range('1{3},2{3},3{4},4{3},5{2}');
$productplan->parent->range('0{12},1{1},2{1},3{1}');
$productplan->title->range('计划1.0{3},计划2.0{3},计划3.0{4},子计划A{2},子计划B{1},子计划C{1},未来计划{1}');
$productplan->deleted->range('0{15}');
$productplan->gen(15);

$story = zenData('story');
$story->id->range('1-30');
$story->product->range('1{6},2{6},3{8},4{6},5{4}');
$story->plan->range('""{10},"1"{3},"2"{3},"3"{4},"1,2"{2},"2,3"{2},"3,4"{2},"5,6"{2},"7,8"{2}');
$story->status->range('draft{5},active{10},reviewing{3},testing{5},verified{4},released{2},closed{1}');
$story->deleted->range('0{30}');
$story->parent->range('0{25},-1{5}');
$story->gen(30);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array(
            1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0', 'parent' => 0)
        ))
    ),
    array(1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0', 'parent' => 0)),
    array(
        1 => (object)array('id' => 1, 'plan' => '1', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '1', 'product' => 1, 'status' => 'testing')
    ),
    array()
)) && p('result') && e('success'); // 步骤1：正常情况统计已计划需求状态

r($pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array())
    ),
    array(),
    array(),
    array(
        1 => (object)array('id' => 1, 'plan' => '', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '', 'product' => 1, 'status' => 'draft')
    )
)) && p('result') && e('success'); // 步骤2：统计未计划需求状态

r($pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array(
            1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
            2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
        ))
    ),
    array(
        1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
        2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
    ),
    array(
        1 => (object)array('id' => 1, 'plan' => '1', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '2', 'product' => 1, 'status' => 'testing'),
        3 => (object)array('id' => 3, 'plan' => '1', 'product' => 1, 'status' => 'verified')
    ),
    array()
)) && p('result') && e('success'); // 步骤3：测试多个计划的需求状态统计

r($pivotTest->getPlanStatusStatisticsTest(
    array(),
    array(),
    array(),
    array()
)) && p('result') && e('success'); // 步骤4：测试空数据情况

r($pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array(
            1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
            2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
        ))
    ),
    array(
        1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
        2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
    ),
    array(
        1 => (object)array('id' => 1, 'plan' => '1,2', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '2,3', 'product' => 1, 'status' => 'testing')
    ),
    array()
)) && p('result') && e('success'); // 步骤5：测试需求属于多个计划的情况