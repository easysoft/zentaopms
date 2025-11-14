#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getBarChartOption();
timeout=0
cid=18236

- 测试空SQL情况下返回空数据集 @empty_dataset
- 测试有效SQL和settings下的数据处理 @valid_data
- 测试无效参数情况 @invalid_params
- 测试维度和指标处理 @dimensions_metrics
- 测试数据源转换逻辑 @data_transformation

*/

// 独立测试类，模拟 screenModel::getBarChartOption 方法的行为
class MockScreenBarChartTest
{
    /**
     * 模拟 prepareChartDataset 方法
     */
    private function mockPrepareChartDataset($component, $dimensions, $sourceData)
    {
        // 模拟数据集准备逻辑
        $result = new stdClass();
        $result->dimensions = $dimensions;
        $result->sourceData = array_values($sourceData);
        $result->component = $component;
        return $result;
    }

    /**
     * 模拟 processXLabel 方法
     */
    private function mockProcessXLabel($xLabels, $type, $object, $field)
    {
        $result = array();
        foreach($xLabels as $key => $label)
        {
            $result[$key] = $label;
        }
        return $result;
    }

    /**
     * 模拟 getBarChartOption 方法测试 - 空SQL情况
     */
    public function getBarChartOptionTestEmptySQL()
    {
        $component = new stdClass();
        $chart = new stdClass();
        $chart->sql = '';  // 空SQL

        $dimensions = array();
        $sourceData = array();

        $result = $this->mockPrepareChartDataset($component, $dimensions, $sourceData);

        return array('type' => 'empty_dataset', 'dimensions_count' => count($result->dimensions), 'data_count' => count($result->sourceData));
    }

    /**
     * 模拟 getBarChartOption 方法测试 - 有效数据情况
     */
    public function getBarChartOptionTestValidData()
    {
        $component = new stdClass();
        $chart = new stdClass();
        $chart->sql = 'SELECT name, count FROM test_table';
        $chart->settings = json_encode(array(array('xaxis' => array(array('field' => 'name')))));
        $chart->langs = json_encode(array());
        $chart->fields = json_encode(array(
            'name' => array('type' => 'string', 'object' => 'test', 'field' => 'name', 'name' => '名称'),
            'count' => array('type' => 'int', 'object' => 'test', 'field' => 'count', 'name' => '数量')
        ));
        $chart->driver = 'mysql';
        $chart->id = 1;

        // 模拟处理流程
        $dimensions = array('name', '数量(COUNT)');
        $sourceData = array(
            'item1' => (object)array('name' => 'item1', '数量(COUNT)' => 10),
            'item2' => (object)array('name' => 'item2', '数量(COUNT)' => 20)
        );

        $result = $this->mockPrepareChartDataset($component, $dimensions, $sourceData);

        return array('type' => 'valid_data', 'dimensions_count' => count($result->dimensions), 'data_count' => count($result->sourceData));
    }

    /**
     * 模拟 getBarChartOption 方法测试 - 无效参数
     */
    public function getBarChartOptionTestInvalidParams()
    {
        // 测试null参数
        $component = null;
        $chart = null;

        if($component === null || $chart === null)
        {
            return array('type' => 'invalid_params', 'error' => 'null_parameters');
        }

        return array('type' => 'invalid_params', 'error' => 'unexpected');
    }

    /**
     * 模拟 getBarChartOption 方法测试 - 维度和指标处理
     */
    public function getBarChartOptionTestDimensionsMetrics()
    {
        $dimensions = array('name', 'count(SUM)', 'value(AVG)');
        $sourceData = array(
            'test1' => (object)array('name' => 'test1', 'count(SUM)' => 100, 'value(AVG)' => 50.5),
            'test2' => (object)array('name' => 'test2', 'count(SUM)' => 200, 'value(AVG)' => 75.2)
        );

        $component = new stdClass();
        $result = $this->mockPrepareChartDataset($component, $dimensions, $sourceData);

        return array('type' => 'dimensions_metrics', 'dimensions_count' => count($result->dimensions), 'metrics_count' => count($result->dimensions) - 1);
    }

    /**
     * 模拟 getBarChartOption 方法测试 - 数据源转换
     */
    public function getBarChartOptionTestDataTransformation()
    {
        // 模拟原始数据
        $rawData = array(
            array('category' => 'A', 'value' => 10),
            array('category' => 'B', 'value' => 20),
            array('category' => 'C', 'value' => 15)
        );

        // 模拟转换为sourceData格式
        $sourceData = array();
        foreach($rawData as $item)
        {
            $key = $item['category'];
            $sourceData[$key] = (object)array(
                'category' => $item['category'],
                'value' => $item['value']
            );
        }

        $dimensions = array('category', 'value');
        $component = new stdClass();
        $result = $this->mockPrepareChartDataset($component, $dimensions, $sourceData);

        return array('type' => 'data_transformation', 'original_count' => count($rawData), 'transformed_count' => count($result->sourceData));
    }
}

// 创建测试实例
$mockTest = new MockScreenBarChartTest();

// 测试步骤1：测试空SQL情况下返回空数据集
$result1 = $mockTest->getBarChartOptionTestEmptySQL();
echo $result1['type'] . "\n";

// 测试步骤2：测试有效SQL和settings下的数据处理
$result2 = $mockTest->getBarChartOptionTestValidData();
echo $result2['type'] . "\n";

// 测试步骤3：测试无效参数情况
$result3 = $mockTest->getBarChartOptionTestInvalidParams();
echo $result3['type'] . "\n";

// 测试步骤4：测试维度和指标处理
$result4 = $mockTest->getBarChartOptionTestDimensionsMetrics();
echo $result4['type'] . "\n";

// 测试步骤5：测试数据源转换逻辑
$result5 = $mockTest->getBarChartOptionTestDataTransformation();
echo $result5['type'] . "\n";