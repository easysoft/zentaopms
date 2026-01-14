#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getMetricTableOption();
timeout=0
cid=18249

- 执行$result->headers @1
- 执行$result->data @1
- 执行$result属性scope @product
- 执行$result属性existingProperty @test_value
- 执行$result属性scope @task

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();

// 创建测试用的metric对象
$metric = new stdClass();
$metric->name = 'Test Metric';
$metric->id = 1;
$metric->scope = 'product';
$metric->dateType = 'month';

// 简化的Mock类，避免复杂的业务逻辑
class SimpleMockMetric {
    public function isObjectMetric($header) {
        return true;
    }

    public function getGroupTable($header, $data, $dateType, $flag) {
        $groupHeader = 'Test Header';
        $groupData = array(array('Item1', 100), array('Item2', 200));
        return array($groupHeader, $groupData);
    }
}

// 直接修改screenModel的getMetricHeaders方法避免复杂处理
class TestScreenModel extends screenModel {
    public function getMetricHeaders($resultHeader, $dateType) {
        return array(array('name' => 'header1'), array('name' => 'header2'));
    }
}

// 使用简化的测试模型
$screenTest->objectModel = new TestScreenModel();
$screenTest->objectModel->metric = new SimpleMockMetric();

// 测试数据
$resultHeader = array(array('name' => 'test'));
$resultData = array(array('data1', 100));

// 步骤1：测试基本功能 - headers属性存在
$result = $screenTest->getMetricTableOptionTest($metric, $resultHeader, $resultData);
r(isset($result->headers)) && p() && e('1');

// 步骤2：测试基本功能 - data属性存在
$result = $screenTest->getMetricTableOptionTest($metric, $resultHeader, $resultData);
r(isset($result->data)) && p() && e('1');

// 步骤3：测试基本功能 - scope属性
$result = $screenTest->getMetricTableOptionTest($metric, $resultHeader, $resultData);
r($result) && p('scope') && e('product');

// 步骤4：测试带component参数
$component = new stdClass();
$component->option = new stdClass();
$component->option->tableOption = new stdClass();
$component->option->tableOption->existingProperty = 'test_value';

$result = $screenTest->getMetricTableOptionTest($metric, $resultHeader, $resultData, $component);
r($result) && p('existingProperty') && e('test_value');

// 步骤5：测试scope字段传递
$metric->scope = 'task';
$result = $screenTest->getMetricTableOptionTest($metric, $resultHeader, $resultData);
r($result) && p('scope') && e('task');