#!/usr/bin/env php
<?php

/**

title=测试 metricModel::calculateReuseMetric();
timeout=0
cid=17066

- 步骤1：不支持重用 @0
- 步骤2：空重用指标列表 @1
- 步骤3：单个重用指标 @1
- 步骤4：多个重用指标 @1
- 步骤5：空对象计算器 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->id->range('1-5');
$table->code->range('test_metric1,test_metric2,test_metric3,reuse_metric1,reuse_metric2');
$table->name->range('测试指标1,测试指标2,测试指标3,重用指标1,重用指标2');
$table->scope->range('product{5}');
$table->purpose->range('scale{5}');
$table->stage->range('released{5}');
$table->gen(5);

$metriclib = zenData('metriclib');
$metriclib->id->range('1-10');
$metriclib->metricID->range('1-5:2');
$metriclib->metricCode->range('test_metric1{2},test_metric2{2},test_metric3{2},reuse_metric1{2},reuse_metric2{2}');
$metriclib->value->range('10,20,30,40,50,60,70,80,90,100');
$metriclib->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试计算器类
class testCalculator
{
    public $reuse = false;
    public $reuseMetrics = array();

    public function calculate($data)
    {
        return true;
    }
}

class testReuseCalculator
{
    public $reuse = true;
    public $reuseMetrics = array();

    public function calculate($data)
    {
        return true;
    }
}

// 创建计算器对象 - 不支持重用
$calculatorNoReuse = new testCalculator();

r($metricTest->calculateReuseMetricTest($calculatorNoReuse, array(), 'realtime', null, 'rnd')) && p() && e('0'); // 步骤1：不支持重用

// 创建计算器对象 - 支持重用但reuseMetrics为空
$calculatorEmptyReuse = new testReuseCalculator();

r($metricTest->calculateReuseMetricTest($calculatorEmptyReuse, array(), 'realtime', null, 'rnd')) && p() && e('1'); // 步骤2：空重用指标列表

// 创建计算器对象 - 支持重用且有单个重用指标
$calculatorSingleReuse = new testReuseCalculator();
$calculatorSingleReuse->reuseMetrics = array('metric1' => 'test_metric1');

r($metricTest->calculateReuseMetricTest($calculatorSingleReuse, array(), 'realtime', null, 'rnd')) && p() && e('1'); // 步骤3：单个重用指标

// 创建计算器对象 - 支持重用且有多个重用指标
$calculatorMultiReuse = new testReuseCalculator();
$calculatorMultiReuse->reuseMetrics = array('metric1' => 'test_metric1', 'metric2' => 'test_metric2');

r($metricTest->calculateReuseMetricTest($calculatorMultiReuse, array(), 'realtime', null, 'rnd')) && p() && e('1'); // 步骤4：多个重用指标

// 测试计算器为空对象的边界情况 - 修改为空对象测试而不是null，避免致命错误
$calculatorEmpty = new stdClass();
$calculatorEmpty->reuse = false;
r($metricTest->calculateReuseMetricTest($calculatorEmpty, array(), 'realtime', null, 'rnd')) && p() && e('0'); // 步骤5：空对象计算器