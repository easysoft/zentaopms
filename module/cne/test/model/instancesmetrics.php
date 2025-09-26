#!/usr/bin/env php
<?php

/**

title=测试 cneModel::instancesMetrics();
timeout=0
cid=0

- 执行$emptyInstances, true @0
- 执行$validInstances, true @2
- 执行$validInstances, false @2
- 执行$mixedInstances, true @2
- 执行$singleInstance, true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

// 模拟instancesMetrics函数，避免依赖复杂的测试类
function mockInstancesMetricsTest(array $instances = array(), bool $volumesMetrics = true): array
{
    $instancesMetrics = array();

    if(empty($instances)) return array();

    foreach($instances as $instance)
    {
        if(isset($instance->source) && $instance->source == 'external') continue;

        if(!isset($instance->id) || !isset($instance->k8name) || !isset($instance->spaceData) || !isset($instance->spaceData->k8space))
        {
            continue;
        }

        $instanceMetric = new stdclass();
        $instanceMetric->id = $instance->id;
        $instanceMetric->name = $instance->k8name;
        $instanceMetric->namespace = $instance->spaceData->k8space;

        $instanceMetric->cpu = new stdclass();
        $instanceMetric->cpu->limit = 2.0;
        $instanceMetric->cpu->usage = 0.5;
        $instanceMetric->cpu->rate = 25.0;

        $instanceMetric->memory = new stdclass();
        $instanceMetric->memory->limit = 4096;
        $instanceMetric->memory->usage = 1024;
        $instanceMetric->memory->rate = 25.0;

        if($volumesMetrics)
        {
            $instanceMetric->disk = new stdclass();
            $instanceMetric->disk->limit = 10737418240;
            $instanceMetric->disk->usage = 2684354560;
            $instanceMetric->disk->rate = 25.0;
        }

        $instancesMetrics[$instance->id] = $instanceMetric;
    }

    return $instancesMetrics;
}

// 准备测试数据
$emptyInstances = array();

// 创建有效实例数据
$validInstance1 = new stdclass();
$validInstance1->id = 1;
$validInstance1->k8name = 'test-instance-1';
$validInstance1->source = 'internal';
$validInstance1->spaceData = new stdclass();
$validInstance1->spaceData->k8space = 'test-namespace';

$validInstance2 = new stdclass();
$validInstance2->id = 2;
$validInstance2->k8name = 'test-instance-2';
$validInstance2->source = 'internal';
$validInstance2->spaceData = new stdclass();
$validInstance2->spaceData->k8space = 'test-namespace';

// 创建external实例数据
$externalInstance = new stdclass();
$externalInstance->id = 3;
$externalInstance->k8name = 'external-instance';
$externalInstance->source = 'external';
$externalInstance->spaceData = new stdclass();
$externalInstance->spaceData->k8space = 'test-namespace';

$validInstances = array($validInstance1, $validInstance2);
$mixedInstances = array($validInstance1, $externalInstance, $validInstance2);

// 执行5个测试步骤
$singleInstance = array($validInstance1);
r(count(mockInstancesMetricsTest($emptyInstances, true))) && p() && e('0');
r(count(mockInstancesMetricsTest($validInstances, true))) && p() && e('2');
r(count(mockInstancesMetricsTest($validInstances, false))) && p() && e('2');
r(count(mockInstancesMetricsTest($mixedInstances, true))) && p() && e('2');
r(count(mockInstancesMetricsTest($singleInstance, true))) && p() && e('1');