#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getMetricChartOption();
timeout=0
cid=0

- 执行screenTest模块的getMetricChartOptionTest方法，参数是$metric, $resultHeader, $resultData 属性backgroundColor @#0B1727FF
- 执行screenTest模块的getMetricChartOptionTest方法，参数是$metric, $resultHeader, $resultData  @alse
- 执行screenTest模块的getMetricChartOptionTest方法，参数是$metric, $resultHeader, $resultData, $component 属性backgroundColor @red
- 执行screenTest模块的getMetricChartOptionTest方法，参数是$metric, $resultHeader, $resultData 属性title @Test Metric
属性text @Test Metric
- 执行screenTest模块的getMetricChartOptionTest方法，参数是$metric, $resultHeader, $resultData 属性legend @white
属性textStyle @white
属性color @white

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

// 创建测试用的metric对象
$metric = new stdClass();
$metric->name = 'Test Metric';
$metric->id = 1;

// 创建测试用的resultHeader和resultData
$resultHeader = array('name', 'value');
$resultData = array(
    array('Product A', 100),
    array('Product B', 200),
    array('Product C', 150)
);

// Mock类定义
class MockMetric {
    public function getEchartsOptions($header, $data) {
        return array(
            'series' => array(array('data' => array(100, 200, 150))),
            'xAxis' => array('data' => array('Product A', 'Product B', 'Product C'))
        );
    }
}

class MockMetricFailed {
    public function getEchartsOptions($header, $data) {
        return false;
    }
}

// 步骤1：正常调用
$screenTest->objectModel->metric = new MockMetric();
r($screenTest->getMetricChartOptionTest($metric, $resultHeader, $resultData)) && p('backgroundColor') && e('#0B1727FF');

// 步骤2：失败情况
$screenTest->objectModel->metric = new MockMetricFailed();
r($screenTest->getMetricChartOptionTest($metric, $resultHeader, $resultData)) && p() && e(false);

// 步骤3：带component参数
$component = new stdClass();
$component->option = new stdClass();
$component->option->chartOption = new stdClass();
$component->option->chartOption->backgroundColor = 'red';
$component->option->chartOption->xAxis = new stdClass();

$screenTest->objectModel->metric = new MockMetric();
r($screenTest->getMetricChartOptionTest($metric, $resultHeader, $resultData, $component)) && p('backgroundColor') && e('red');

// 步骤4：测试标题设置
$screenTest->objectModel->metric = new MockMetric();
r($screenTest->getMetricChartOptionTest($metric, $resultHeader, $resultData)) && p('title,text') && e('Test Metric');

// 步骤5：测试图例设置
$screenTest->objectModel->metric = new MockMetric();
r($screenTest->getMetricChartOptionTest($metric, $resultHeader, $resultData)) && p('legend,textStyle,color') && e('white');