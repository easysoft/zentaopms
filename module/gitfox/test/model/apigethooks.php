#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigethooks();
timeout=0
cid=0

- 步骤 1：apiGetHooks 不产生 dao 错误 @0
- 步骤 2：apiGetHooks 返回值类型为 array 或 bool @1
- 步骤 3：apiGetHooks 查询结果标记为 0 或 1 @1
- 步骤 4：删除该 webhook 后查询不到它 @0
- 步骤 5：删除后 apiGetHooks 返回值类型为 array 或 bool @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$hookURL    = 'http://example.com/list-hook-' . uniqid();
$hook       = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => $hookURL, 'displayName' => 'list-hook-' . uniqid()));
$hookID     = is_object($hook) && isset($hook->id) ? (int)$hook->id : 0;

r($gitfoxTest->apiGetHooksErrorTest(1)) && p() && e('0');
r(in_array($gitfoxTest->apiGetHooksTypeTest(1), array('array', 'bool'))) && p() && e('1');
r(in_array($gitfoxTest->apiGetHooksContainsUrlTest(1, $hookURL), array(0, 1))) && p() && e('1');
$gitfoxTest->apiDeleteWebhookTest(1, $hookID);
r($gitfoxTest->apiGetHooksContainsUrlTest(1, $hookURL)) && p() && e('0');
r(in_array($gitfoxTest->apiGetHooksTypeTest(1), array('array', 'bool'))) && p() && e('1');
