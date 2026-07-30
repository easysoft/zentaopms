#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiUpdateWebhook();
timeout=0
cid=0

- 步骤 1：apiUpdateWebhook 不产生 dao 错误 @0
- 步骤 2：apiUpdateWebhook 更新后的 url 正确 @1
- 步骤 3：apiUpdateWebhook 返回值类型为 object @object
- 步骤 4：apiUpdateWebhook 更新后的 enabled 和 insecure 正确 @1,1
- 步骤 5：apiUpdateWebhook 返回的 id 正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$hookError  = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/update-hook-' . uniqid(), 'displayName' => 'update-hook-' . uniqid()));
$hookURL    = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/update-hook-' . uniqid(), 'displayName' => 'update-hook-' . uniqid()));
$hookType   = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/update-hook-' . uniqid(), 'displayName' => 'update-hook-' . uniqid()));
$hookFlags  = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/update-hook-' . uniqid(), 'displayName' => 'update-hook-' . uniqid()));
$hookID     = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/update-hook-' . uniqid(), 'displayName' => 'update-hook-' . uniqid()));
$updateDataError = (object)array('url' => 'http://example.com/update-hook-target-' . uniqid(), 'displayName' => 'update-hook-target-' . uniqid(), 'enabled' => true, 'insecure' => true);
$updateDataURL   = (object)array('url' => 'http://example.com/update-hook-target-' . uniqid(), 'displayName' => 'update-hook-target-' . uniqid(), 'enabled' => true, 'insecure' => true);
$updateDataType  = (object)array('url' => 'http://example.com/update-hook-target-' . uniqid(), 'displayName' => 'update-hook-target-' . uniqid(), 'enabled' => true, 'insecure' => true);
$updateDataFlags = (object)array('url' => 'http://example.com/update-hook-target-' . uniqid(), 'displayName' => 'update-hook-target-' . uniqid(), 'enabled' => true, 'insecure' => true);
$updateDataID    = (object)array('url' => 'http://example.com/update-hook-target-' . uniqid(), 'displayName' => 'update-hook-target-' . uniqid(), 'enabled' => true, 'insecure' => true);

r($gitfoxTest->apiUpdateWebhookErrorTest(1, (int)$hookError->id, $updateDataError)) && p() && e('0');
r($gitfoxTest->apiUpdateWebhookUrlMatchesTest(1, (int)$hookURL->id, $updateDataURL)) && p() && e('1');
r($gitfoxTest->apiUpdateWebhookTypeTest(1, (int)$hookType->id, $updateDataType)) && p() && e('object');
r($gitfoxTest->apiUpdateWebhookTest(1, (int)$hookFlags->id, $updateDataFlags)) && p('enabled,insecure') && e('1,1');
r($gitfoxTest->apiUpdateWebhookIDMatchesTest(1, (int)$hookID->id, $updateDataID)) && p() && e('1');
