#!/usr/bin/env php
<?php

/**

title=测试 cneModel::tryAllocate();
timeout=0
cid=15632

- 步骤1：空资源数组会封装为空 requests @/api/cne/system/resource/try-allocate,0
- 步骤2：单条资源请求会完整透传 @1,0.2
- 步骤3：多条资源请求保持顺序和数量 @2,536870912
- 步骤4：极大资源值不会在方法内被改写 @100
- 步骤5：请求体使用对象并透传认证头 @1,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

class tryAllocateMockModel extends cneModel
{
    public function apiPost(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $requests = $data->requests ?? array();
        $lastRequest = empty($requests) ? array() : end($requests);

        $response = new stdclass();
        $response->url             = $url;
        $response->requestCount    = count($requests);
        $response->firstCPU        = empty($requests) ? 0 : ($requests[0]['cpu'] ?? 0);
        $response->lastMemory      = $lastRequest['memory'] ?? 0;
        $response->isObjectPayload = is_object($data) ? 1 : 0;
        $response->headerCount     = count($header);
        return $response;
    }
}

$testModel = new tryAllocateMockModel();

$result1 = $cneTest->tryAllocateTest(array(), $testModel);
$result2 = $cneTest->tryAllocateTest(array(array('cpu' => 0.2, 'memory' => 268435456)), $testModel);
$result3 = $cneTest->tryAllocateTest(array(array('cpu' => 0.2, 'memory' => 268435456), array('cpu' => 0.5, 'memory' => 536870912)), $testModel);
$result4 = $cneTest->tryAllocateTest(array(array('cpu' => 100, 'memory' => 268435456)), $testModel);
$result5 = $cneTest->tryAllocateTest(array(array('cpu' => 0.2, 'memory' => 1073741824000)), $testModel);

r($result1) && p('url,requestCount') && e('/api/cne/system/resource/try-allocate,0');
r($result2) && p('requestCount,firstCPU') && e('1,0.2');
r($result3) && p('requestCount,lastMemory') && e('2,536870912');
r($result4) && p('firstCPU') && e('100');
r((object) array('isObjectPayload' => $result5->isObjectPayload, 'hasHeaders' => $result5->headerCount > 0 ? 1 : 0)) && p('isObjectPayload,hasHeaders') && e('1,1');
