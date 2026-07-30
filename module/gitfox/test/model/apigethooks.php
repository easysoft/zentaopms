#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigethooks();
timeout=0
cid=0

- 步骤 1：apiGetHooks 不产生 dao 错误 @0
- 步骤 2：apiGetHooks 返回值类型为 array @array
- 步骤 3：apiGetHooks 能查到新建 webhook @1
- 步骤 4：删除该 webhook 后查询不到它 @0
- 步骤 5：删除后 apiGetHooks 仍返回 array @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$hookURL    = 'http://example.com/list-hook-' . uniqid();
$hook       = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => $hookURL, 'displayName' => 'list-hook-' . uniqid()));

r($gitfoxTest->apiGetHooksErrorTest(1)) && p() && e('0');
r($gitfoxTest->apiGetHooksTypeTest(1)) && p() && e('array');
r($gitfoxTest->apiGetHooksContainsUrlTest(1, $hookURL)) && p() && e('1');
$gitfoxTest->apiDeleteWebhookTest(1, (int)$hook->id);
r($gitfoxTest->apiGetHooksContainsUrlTest(1, $hookURL)) && p() && e('0');
r($gitfoxTest->apiGetHooksTypeTest(1)) && p() && e('array');
