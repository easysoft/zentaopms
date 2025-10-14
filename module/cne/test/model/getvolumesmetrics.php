#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getVolumesMetrics();
timeout=0
cid=0

- 执行cneTest模块的getVolumesMetricsTest方法，参数是createMockInstance
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 执行cneTest模块的getVolumesMetricsTest方法，参数是createMockInstance
 - 属性limit @10737418240
 - 属性usage @5368709120
 - 属性rate @50
- 执行cneTest模块的getVolumesMetricsTest方法，参数是createMockInstance
 - 属性limit @5368709120
 - 属性usage @5368709120
 - 属性rate @100
- 执行cneTest模块的getVolumesMetricsTest方法，参数是null
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01
- 执行cneTest模块的getVolumesMetricsTest方法，参数是createMockInstance
 - 属性limit @0
 - 属性usage @0
 - 属性rate @0.01

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

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

r($cneTest->getVolumesMetricsTest(createMockInstance(1))) && p('limit,usage,rate') && e('0,0,0.01');
r($cneTest->getVolumesMetricsTest(createMockInstance(2))) && p('limit,usage,rate') && e('10737418240,5368709120,50');
r($cneTest->getVolumesMetricsTest(createMockInstance(3))) && p('limit,usage,rate') && e('5368709120,5368709120,100');
r($cneTest->getVolumesMetricsTest(null)) && p('limit,usage,rate') && e('0,0,0.01');
r($cneTest->getVolumesMetricsTest(createMockInstance(999))) && p('limit,usage,rate') && e('0,0,0.01');