#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateHook();
timeout=0
cid=0

- 步骤 1：apiCreateHook 不产生 dao 错误 @0
- 步骤 2：无 url 时 apiCreateHook 返回 false @0
- 步骤 3：唯一 displayName 的 webhook 创建成功 @1
- 步骤 4：再次使用新的唯一 displayName 创建时返回 object @object
- 步骤 5：唯一 displayName 的 webhook 创建不产生 dao 错误 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$hookOne    = (object)array('url' => 'http://example.com/hook-create-' . uniqid(), 'displayName' => 'hook-create-' . uniqid());
$hookTwo    = (object)array('url' => 'http://example.com/hook-create-' . uniqid(), 'displayName' => 'hook-create-' . uniqid());
$hookThree  = (object)array('url' => 'http://example.com/hook-create-' . uniqid(), 'displayName' => 'hook-create-' . uniqid());

r($gitfoxTest->apiCreateHookErrorTest(1, (object)array('name' => 'test'))) && p() && e('0');
r($gitfoxTest->apiCreateHookTest(1, (object)array('name' => 'test'))) && p() && e('0');
r($gitfoxTest->apiCreateHookUrlMatchesTest(1, $hookOne)) && p() && e('1');
r($gitfoxTest->apiCreateHookTypeTest(1, $hookTwo)) && p() && e('object');
r($gitfoxTest->apiCreateHookErrorTest(1, $hookThree)) && p() && e('0');
