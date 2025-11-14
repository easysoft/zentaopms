#!/usr/bin/env php
<?php

/**

title=测试 metricModel::calculateSingleMetric();
timeout=0
cid=17067

- 步骤1：正常情况，支持单查询的calculator @1
- 步骤2：不支持单查询的calculator @0
- 步骤3：支持单查询但使用更少字段的calculator @1
- 步骤4：使用不同vision参数 @1
- 步骤5：空calculator对象 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. 准备测试数据
su('admin');

// 加载基础计算器类
include_once dirname(__FILE__, 4) . '/metric/calc.class.php';

// 3. 创建测试实例
$metricTest = new metricTest();

// 创建测试计算器类
class testSingleCalculator extends baseCalc
{
    public function setDAO($dao) { $this->dao = $dao; }
    public function setSCM($scm) { $this->scm = $scm; }
    public function setSingleSql($sql) { $this->singleSql = $sql; }
    public function enableSingleQuery() { $this->useSingleQuery = true; }
}

// 创建支持单查询的calculator
$calculatorWithSingleQuery = new testSingleCalculator();
$calculatorWithSingleQuery->supportSingleQuery = true;
$calculatorWithSingleQuery->dataset = 'getBugs';
$calculatorWithSingleQuery->fieldList = array('t1.id', 't1.product');

// 创建不支持单查询的calculator
$calculatorWithoutSingleQuery = new testSingleCalculator();
$calculatorWithoutSingleQuery->supportSingleQuery = false;
$calculatorWithoutSingleQuery->dataset = 'getBugs';
$calculatorWithoutSingleQuery->fieldList = array('t1.id', 't1.product');

// 创建支持单查询但使用更少字段的calculator
$calculatorLessFields = new testSingleCalculator();
$calculatorLessFields->supportSingleQuery = true;
$calculatorLessFields->dataset = 'getBugs';
$calculatorLessFields->fieldList = array('t1.id');

// 创建null calculator
$calculatorNull = null;

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->calculateSingleMetricTest($calculatorWithSingleQuery, 'rnd')) && p() && e('1'); // 步骤1：正常情况，支持单查询的calculator
r($metricTest->calculateSingleMetricTest($calculatorWithoutSingleQuery, 'rnd')) && p() && e('0'); // 步骤2：不支持单查询的calculator
r($metricTest->calculateSingleMetricTest($calculatorLessFields, 'rnd')) && p() && e('1'); // 步骤3：支持单查询但使用更少字段的calculator
r($metricTest->calculateSingleMetricTest($calculatorWithSingleQuery, 'lite')) && p() && e('1'); // 步骤4：使用不同vision参数
r($metricTest->calculateSingleMetricTest($calculatorNull, 'rnd')) && p() && e('0'); // 步骤5：空calculator对象