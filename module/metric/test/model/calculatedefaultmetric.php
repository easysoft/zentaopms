#!/usr/bin/env php
<?php

/**

title=测试 metricModel::calculateDefaultMetric();
timeout=0
cid=17064

- 步骤1：正常情况，有数据集的calculator @1
- 步骤2：有数据集且使用SCM的calculator @1
- 步骤3：空calculator对象 @0
- 步骤4：使用不同vision参数 @1
- 步骤5：空数据集calculator @1

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
class testDefaultCalculator extends baseCalc
{
    public function setDAO($dao) { $this->dao = $dao; }
    public function setSCM($scm) { $this->scm = $scm; }
    public function calculate($row) { return true; }
}

// 创建有数据集的calculator
$calculatorWithDataset = new testDefaultCalculator();
$calculatorWithDataset->dataset = 'getBugs';
$calculatorWithDataset->fieldList = array('t1.id', 't1.product');
$calculatorWithDataset->useSCM = false;

// 创建有数据集且使用SCM的calculator
$calculatorWithSCM = new testDefaultCalculator();
$calculatorWithSCM->dataset = 'getBugs';
$calculatorWithSCM->fieldList = array('t1.id', 't1.product');
$calculatorWithSCM->useSCM = true;

// 创建空calculator
$calculatorNull = null;

// 创建用于测试不同vision的calculator
$calculatorLiteVision = new testDefaultCalculator();
$calculatorLiteVision->dataset = 'getBugs';
$calculatorLiteVision->fieldList = array('t1.id', 't1.product');
$calculatorLiteVision->useSCM = false;

// 创建空数据集的calculator
$calculatorEmptyDataset = new testDefaultCalculator();
$calculatorEmptyDataset->dataset = '';
$calculatorEmptyDataset->fieldList = array();
$calculatorEmptyDataset->useSCM = false;

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->calculateDefaultMetricTest($calculatorWithDataset, 'rnd')) && p() && e('1'); // 步骤1：正常情况，有数据集的calculator
r($metricTest->calculateDefaultMetricTest($calculatorWithSCM, 'rnd')) && p() && e('1'); // 步骤2：有数据集且使用SCM的calculator
r($metricTest->calculateDefaultMetricTest($calculatorNull, 'rnd')) && p() && e('0'); // 步骤3：空calculator对象
r($metricTest->calculateDefaultMetricTest($calculatorLiteVision, 'lite')) && p() && e('1'); // 步骤4：使用不同vision参数
r($metricTest->calculateDefaultMetricTest($calculatorEmptyDataset, 'rnd')) && p() && e('1'); // 步骤5：空数据集calculator