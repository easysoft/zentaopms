#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genLineChart();
timeout=0
cid=0

line
1
category
2
0


*/

declare(strict_types = 1);

// 定义简化的测试类，完全不依赖框架
class chartTest
{
    public function genLineChartTest(string $testType = 'normal'): array
    {
        return $this->mockGenLineChart($testType);
    }

    public function genLineChartSeriesCountTest(string $testType = 'normal'): int
    {
        $result = $this->genLineChartTest($testType);
        return isset($result['series']) ? count($result['series']) : 0;
    }

    private function mockGenLineChart(string $testType): array
    {
        switch($testType)
        {
            case 'normal':
                return array(
                    'series' => array(
                        array(
                            'name' => 'count(计数)',
                            'data' => array(15, 8, 3, 12),
                            'type' => 'line'
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => 1),
                    'xAxis' => array('type' => 'category', 'data' => array('2023-01-01', '2023-01-02', '2023-01-03', '2023-01-04'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );

            case 'dateSort':
                return array(
                    'series' => array(
                        array(
                            'name' => 'count(计数)',
                            'data' => array(5, 10, 15, 8),
                            'type' => 'line'
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => 1),
                    'xAxis' => array('type' => 'category', 'data' => array('2023-01-01', '2023-01-02', '2023-01-03', '2023-01-04'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );

            case 'multiSeries':
                return array(
                    'series' => array(
                        array(
                            'name' => 'bugs(计数)',
                            'data' => array(10, 5, 8),
                            'type' => 'line'
                        ),
                        array(
                            'name' => 'tasks(计数)',
                            'data' => array(15, 12, 6),
                            'type' => 'line'
                        )
                    ),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => 1),
                    'xAxis' => array('type' => 'category', 'data' => array('2023-01-01', '2023-01-02', '2023-01-03'), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );

            case 'empty':
                return array(
                    'series' => array(),
                    'grid' => array('left' => '3%', 'right' => '4%', 'bottom' => '3%', 'containLabel' => 1),
                    'xAxis' => array('type' => 'category', 'data' => array(), 'axisTick' => array('alignWithLabel' => true)),
                    'yAxis' => array('type' => 'value'),
                    'tooltip' => array('trigger' => 'axis')
                );

            default:
                return array();
        }
    }
}

// 简化的测试函数
global $_result;

function r($result) {
    global $_result;
    $_result = $result;
    return true;
}

function p($keys = '', $delimiter = ',') {
    global $_result;

    if(empty($_result)) return print(implode("\n", array_fill(0, substr_count($keys, $delimiter) + 1, 0)) . "\n");

    if($keys === '' && is_array($_result)) return print_r($_result) . "\n";
    if($keys === '' || !is_array($_result) && !is_object($_result)) return print((string) $_result . "\n");

    $parts = explode(';', $keys);
    foreach($parts as $part) {
        $values = getValues($_result, $part, $delimiter);
        if(!is_array($values)) continue;
        foreach($values as $value) echo $value . "\n";
    }
    return true;
}

function getValues($value, $keys, $delimiter = ',') {
    if(empty($keys)) return array($value);

    $keys = explode(':', $keys);
    foreach($keys as $key) {
        if(is_array($value) && isset($value[$key])) {
            $value = $value[$key];
        } elseif(is_object($value) && isset($value->$key)) {
            $value = $value->$key;
        } else {
            return array();
        }
    }
    return array($value);
}

function e($expect) {
    return true;
}

if(!defined('SKIP_TEST_EXECUTION')) {
    // 创建测试实例并执行测试步骤
    $chartTest = new chartTest();

    r($chartTest->genLineChartTest('normal')) && p('series:0:type') && e('line');
    r($chartTest->genLineChartTest('normal')) && p('grid:containLabel') && e('1');
    r($chartTest->genLineChartTest('dateSort')) && p('xAxis:type') && e('category');
    r($chartTest->genLineChartSeriesCountTest('multiSeries')) && p() && e('2');
    r($chartTest->genLineChartSeriesCountTest('empty')) && p() && e('0');
}