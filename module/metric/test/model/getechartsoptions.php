#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getEchartsOptions();
timeout=0
cid=17096

- 执行metricTest模块的getEchartsOptionsTest方法，参数是$emptyHeader, $emptyData, 'line'  @0
- 执行metricTest模块的getEchartsOptionsTest方法，参数是$timeHeader, $timeData, 'line'  @1
- 执行metricTest模块的getEchartsOptionsTest方法，参数是$objectHeader, $objectData, 'line'  @1
- 执行metricTest模块的getEchartsOptionsTest方法，参数是$fourColHeader, $objectData, 'bar'  @1
- 执行metricTest模块的getEchartsOptionsTest方法，参数是$pieHeader, $pieData, 'pie'  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
// 不需要数据库数据准备，直接测试方法逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：测试空参数情况
$emptyHeader = array();
$emptyData = array();
r($metricTest->getEchartsOptionsTest($emptyHeader, $emptyData, 'line')) && p() && e('0');

// 步骤2：测试两列header有数据时返回非空结果
$timeHeader = array(
    array('name' => 'date', 'title' => '日期'),
    array('name' => 'value', 'title' => '值')
);
$timeData = array();
$dataObj1 = new stdClass();
$dataObj1->date = '2023-01-01';
$dataObj1->value = 10;
$dataObj1->calcTime = '2023-01-01 10:00:00';
$timeData[] = $dataObj1;
r($metricTest->getEchartsOptionsTest($timeHeader, $timeData, 'line')) && p() && e('1');

// 步骤3：测试三列header对象度量数据返回非空结果
$objectHeader = array(
    array('name' => 'scope', 'title' => '范围'),
    array('name' => 'date', 'title' => '日期'),
    array('name' => 'value', 'title' => '值')
);
$objectData = array();
$objData1 = new stdClass();
$objData1->scope = 'product1';
$objData1->value = 15;
$objData1->calcTime = '2023-01-01 10:00:00';
$objectData[] = $objData1;
r($metricTest->getEchartsOptionsTest($objectHeader, $objectData, 'line')) && p() && e('1');

// 步骤4：测试四列header返回非空结果
$fourColHeader = array(
    array('name' => 'scope', 'title' => '范围'),
    array('name' => 'date', 'title' => '日期'),
    array('name' => 'value', 'title' => '值'),
    array('name' => 'calcTime', 'title' => '计算时间')
);
r($metricTest->getEchartsOptionsTest($fourColHeader, $objectData, 'bar')) && p() && e('1');

// 步骤5：测试pie图表类型返回非空结果
$pieHeader = array(
    array('name' => 'name', 'title' => '名称'),
    array('name' => 'value', 'title' => '值')
);
$pieData = array();
$pieObj1 = new stdClass();
$pieObj1->name = 'TypeA';
$pieObj1->value = 30;
$pieData[] = $pieObj1;
r($metricTest->getEchartsOptionsTest($pieHeader, $pieData, 'pie')) && p() && e('1');