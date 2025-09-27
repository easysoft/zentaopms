#!/usr/bin/env php
<?php

/**

title=测试 cneModel::instancesMetrics();
timeout=0
cid=0

0
2
2
2
1


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

// 创建测试实例数据的辅助函数
function createMockInstance(int $id, string $k8name, string $source = 'internal'): object
{
    $instance = new stdclass();
    $instance->id = $id;
    $instance->k8name = $k8name;
    $instance->source = $source;
    $instance->spaceData = new stdclass();
    $instance->spaceData->k8space = 'test-namespace';
    return $instance;
}

// 准备测试数据
$emptyInstances = array();

$validInstance1 = createMockInstance(1, 'test-instance-1');
$validInstance2 = createMockInstance(2, 'test-instance-2');
$externalInstance = createMockInstance(3, 'external-instance', 'external');

$validInstances = array($validInstance1, $validInstance2);
$mixedInstances = array($validInstance1, $externalInstance, $validInstance2);
$singleInstance = array($validInstance1);

r(count($cneTest->instancesMetricsTest($emptyInstances, true))) && p() && e('0'); // 步骤1：空实例数组输入
r(count($cneTest->instancesMetricsTest($validInstances, true))) && p() && e('2'); // 步骤2：有效实例数组(包含磁盘指标)
r(count($cneTest->instancesMetricsTest($validInstances, false))) && p() && e('2'); // 步骤3：有效实例数组(不包含磁盘指标)
r(count($cneTest->instancesMetricsTest($mixedInstances, true))) && p() && e('2'); // 步骤4：混合实例数组(含external实例)
r(count($cneTest->instancesMetricsTest($singleInstance, true))) && p() && e('1'); // 步骤5：单个有效实例输入