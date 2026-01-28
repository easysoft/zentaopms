#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDataStatement();
timeout=0
cid=17089

- 步骤1：正常情况，返回statement对象 @1
- 步骤2：返回SQL字符串 @1
- 步骤3：使用SCM @1
- 步骤4：空calculator @0
- 步骤5：不同vision参数 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 创建测试数据
su('admin');

// 加载基础计算器类
include_once dirname(__FILE__, 4) . '/metric/calc.class.php';

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 创建模拟的calculator对象，继承自baseCalc
class testCalculator extends baseCalc
{
    public function setDAO($dao) { $this->dao = $dao; }
    public function setSCM($scm) { $this->scm = $scm; }
}

$calculatorWithDataset = new testCalculator();
$calculatorWithDataset->dataset = 'getBugs';
$calculatorWithDataset->fieldList = array('t1.product', 't1.id');
$calculatorWithDataset->useSCM = false;

$calculatorWithSCM = new testCalculator();
$calculatorWithSCM->dataset = 'getBugs';
$calculatorWithSCM->fieldList = array('t1.product');
$calculatorWithSCM->useSCM = true;

$calculatorEmpty = new testCalculator();
$calculatorEmpty->dataset = '';
$calculatorEmpty->fieldList = array();
$calculatorEmpty->useSCM = false;

// 4. 强制要求：必须包含至少5个测试步骤
r(is_object($metricTest->getDataStatementTest($calculatorWithDataset, 'statement', 'rnd'))) && p() && e('1'); // 步骤1：正常情况，返回statement对象
r(is_string($metricTest->getDataStatementTest($calculatorWithDataset, 'sql', 'rnd'))) && p() && e('1'); // 步骤2：返回SQL字符串
r(is_object($metricTest->getDataStatementTest($calculatorWithSCM, 'statement', 'rnd'))) && p() && e('1'); // 步骤3：使用SCM
r($metricTest->getDataStatementTest($calculatorEmpty, 'statement', 'rnd')) && p() && e('0'); // 步骤4：空calculator
r(is_object($metricTest->getDataStatementTest($calculatorWithDataset, 'statement', 'lite'))) && p() && e('1'); // 步骤5：不同vision参数