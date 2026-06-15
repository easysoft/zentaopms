#!/usr/bin/env php
<?php

/**

title=测试 cneModel::stopApp();
timeout=0
cid=0

- 步骤1：完整参数会调用停止接口并保留默认通道 @/api/cne/app/stop,stable
- 步骤2：channel 为空时自动补齐默认通道 @stable
- 步骤3：自定义 channel 会原样透传 @custom-channel
- 步骤4：缺少 name 字段时不会被方法补齐 @0
- 步骤5：调用时会透传认证头和命名空间 @1,test-namespace

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

class stopAppMockModel extends cneModel
{
    public function apiPost(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $response = new stdclass();
        $response->url         = $url;
        $response->channel     = $data->channel ?? '';
        $response->namespace   = $data->namespace ?? '';
        $response->hasName     = property_exists($data, 'name') ? 1 : 0;
        $response->headerCount = count($header);
        return $response;
    }
}

function createStopParams(string $channel = 'stable'): object
{
    $apiParams = new stdclass();
    $apiParams->cluster   = '';
    $apiParams->name      = 'test-zentao-app';
    $apiParams->chart     = 'zentao';
    $apiParams->namespace = 'test-namespace';
    $apiParams->channel   = $channel;
    return $apiParams;
}

$testModel = new stopAppMockModel();

$result1 = $cneTest->stopAppTest(createStopParams(), $testModel);
$result2 = $cneTest->stopAppTest(createStopParams(''), $testModel);
$result3 = $cneTest->stopAppTest(createStopParams('custom-channel'), $testModel);

$missingName = new stdclass();
$missingName->cluster   = '';
$missingName->namespace = 'test-namespace';
$missingName->channel   = '';
$result4 = $cneTest->stopAppTest($missingName, $testModel);
$result5 = $cneTest->stopAppTest(createStopParams(), $testModel);

r($result1) && p('url,channel') && e('/api/cne/app/stop,stable');
r($result2) && p('channel') && e('stable');
r($result3) && p('channel') && e('custom-channel');
r($result4) && p('hasName') && e('0');
r((object) array('hasHeaders' => $result5->headerCount > 0 ? 1 : 0, 'namespace' => $result5->namespace)) && p('hasHeaders,namespace') && e('1,test-namespace');
