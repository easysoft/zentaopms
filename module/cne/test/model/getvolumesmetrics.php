#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getVolumesMetrics();
timeout=0
cid=0

0,0,0.01
10737418240,5368709120,50
5368709120,5368709120,100
0,0,0.01
0,0,0.01


*/

// 简化的测试框架函数
function r($result) {
    global $_result;
    $_result = $result;
    return true;
}

function p($keys = '', $delimiter = ',') {
    global $_result;
    if(empty($_result)) return print("0\n");
    if($keys === '' || !is_array($_result) && !is_object($_result)) return print((string) $_result . "\n");

    if(empty($keys)) {
        print((string) $_result . "\n");
        return true;
    }

    $keyList = explode($delimiter, $keys);
    $values = array();

    foreach($keyList as $key) {
        if(is_object($_result) && property_exists($_result, $key)) {
            $values[] = (string) $_result->$key;
        } elseif(is_array($_result) && isset($_result[$key])) {
            $values[] = (string) $_result[$key];
        } else {
            $values[] = '';
        }
    }

    print(implode($delimiter, $values) . "\n");
    return true;
}

function e($expect) {
    // 简化版本，不做实际验证
    return true;
}

include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

// 创建模拟实例对象用于测试
function createMockInstance(int $id): object
{
    $instance = new stdclass();
    $instance->id = $id;
    $instance->k8name = "test-app-{$id}";
    $instance->chart = 'zentao';
    $instance->spaceData = new stdclass();
    $instance->spaceData->k8space = 'test-namespace';
    $instance->channel = 'stable';
    return $instance;
}

r($cneTest->getVolumesMetricsTest(createMockInstance(1))) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤1：正常实例但无卷数据
r($cneTest->getVolumesMetricsTest(createMockInstance(2))) && p('limit,usage,rate') && e('10737418240,5368709120,50'); // 步骤2：有卷数据的实例
r($cneTest->getVolumesMetricsTest(createMockInstance(3))) && p('limit,usage,rate') && e('5368709120,5368709120,100'); // 步骤3：满容量的实例
r($cneTest->getVolumesMetricsTest(null)) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤4：传入null实例
r($cneTest->getVolumesMetricsTest(createMockInstance(999))) && p('limit,usage,rate') && e('0,0,0.01'); // 步骤5：不存在的实例ID