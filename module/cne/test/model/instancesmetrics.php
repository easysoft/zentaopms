#!/usr/bin/env php
<?php

/**

title=测试 cneModel::instancesMetrics();
timeout=0
cid=0

- 步骤1：空实例列表返回空结果 @0
- 步骤2：外部实例不会进入统计请求 @2,2
- 步骤3：CPU 指标会按方法规则取整并修正上限 @1.2346,1.2346,100
- 步骤4：卷度量会写入磁盘指标 @50,25,50
- 步骤5：负数内存指标会归零 @0,0,0
- 步骤6：关闭卷度量时不会追加 disk 字段 @0
- 步骤7：接口失败时保留默认 CPU 指标 @0,0,0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

class instancesMetricsMockModel extends cneModel
{
    public object $mockApiResult;
    public array  $capturedApps = array();
    public array  $diskMetrics  = array();
    public array  $capturedNames = array();

    public function apiPost(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $this->capturedApps = $data['apps'] ?? array();
        $this->capturedNames = array_map(static fn($app) => $app->name, $this->capturedApps);

        if(empty($this->capturedNames)) return (object) array('code' => 200, 'data' => array());

        $result = clone $this->mockApiResult;
        $result->data = array_values(array_filter($this->mockApiResult->data, fn($metric) => in_array($metric->name, $this->capturedNames)));
        return $result;
    }

    public function getVolumesMetrics(object $instance): object
    {
        if(isset($this->diskMetrics[$instance->k8name])) return $this->diskMetrics[$instance->k8name];

        $metric = new stdclass();
        $metric->limit = 0;
        $metric->usage = 0;
        $metric->rate  = 0.01;
        return $metric;
    }
}

function createInstance(int $id, string $k8name, string $k8space, string $source = 'internal'): object
{
    $instance = new stdclass();
    $instance->id = $id;
    $instance->k8name = $k8name;
    $instance->source = $source;
    $instance->spaceData = new stdclass();
    $instance->spaceData->k8space = $k8space;
    return $instance;
}

function createDiskMetric(int|float $limit, int|float $usage, float $rate): object
{
    $metric = new stdclass();
    $metric->limit = $limit;
    $metric->usage = $usage;
    $metric->rate  = $rate;
    return $metric;
}

function createAppMetric(string $name, float $cpuUsage, float $cpuLimit, int $memoryUsage, int $memoryLimit): object
{
    $metric = new stdclass();
    $metric->name = $name;
    $metric->metrics = new stdclass();
    $metric->metrics->cpu = new stdclass();
    $metric->metrics->cpu->usage = $cpuUsage;
    $metric->metrics->cpu->limit = $cpuLimit;
    $metric->metrics->memory = new stdclass();
    $metric->metrics->memory->usage = $memoryUsage;
    $metric->metrics->memory->limit = $memoryLimit;
    return $metric;
}

$testModel = new instancesMetricsMockModel();
$testModel->diskMetrics = array(
    'test-app-1' => createDiskMetric(100, 40, 40),
    'test-app-2' => createDiskMetric(50, 25, 50)
);
$testModel->mockApiResult = (object) array(
    'code' => 200,
    'data' => array(
        createAppMetric('test-app-1', 1.23456, 1.2, 2147483648, 1073741824),
        createAppMetric('test-app-2', 0.5, 1, -1, 0)
    )
);

$internalInstanceA = createInstance(1, 'test-app-1', 'namespace-1');
$internalInstanceB = createInstance(2, 'test-app-2', 'namespace-2');
$externalInstance  = createInstance(3, 'external-app', 'namespace-3', 'external');

$emptyResult = $cneTest->instancesMetricsTest(array(), true, $testModel);
$emptyPostedApps = count($testModel->capturedApps);
$mixedResult = $cneTest->instancesMetricsTest(array($internalInstanceA, $internalInstanceB, $externalInstance), true, $testModel);
$mixedPostedApps = count($testModel->capturedApps);
$withoutDisk = $cneTest->instancesMetricsTest(array($internalInstanceA), false, $testModel);

$testModel->mockApiResult = (object) array('code' => 500, 'data' => array());
$fallbackResult = $cneTest->instancesMetricsTest(array($internalInstanceA), true, $testModel);

r((object) array('count' => count($emptyResult), 'postedApps' => $emptyPostedApps)) && p('count,postedApps') && e('0,0');
r((object) array('count' => count($mixedResult), 'postedApps' => $mixedPostedApps)) && p('count,postedApps') && e('2,2');
r($mixedResult[1]->cpu) && p('usage,limit,rate') && e('1.2346,1.2346,100');
r($mixedResult[2]->disk) && p('limit,usage,rate') && e('50,25,50');
r($mixedResult[2]->memory) && p('usage,limit,rate') && e('0,0,0');
r(isset($withoutDisk[1]->disk) ? 1 : 0) && p() && e('0');
r($fallbackResult[1]->cpu) && p('usage,limit,rate') && e('0,0,0');
