#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateHook();
timeout=0
cid=0

- 步骤 1：apiCreateHook 不产生 dao 错误 @0
- 步骤 2：无 url 时 apiCreateHook 返回 false @0
- 步骤 3：正常参数 apiCreateHook 返回非 null @1
- 步骤 4：apiCreateHook 返回值类型正确 @1
- 步骤 5：无 url 再次调用返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r($model->apiCreateHook(1, (object)array('name' => 'test'))) && p() && e('0');
r(!is_null($model->apiCreateHook(1, (object)array('url' => 'http://ex.com/hook')))) && p() && e('1');
r(is_bool($model->apiCreateHook(1, (object)array('url' => 'http://ex.com/hook'))) || is_object($model->apiCreateHook(1, (object)array('url' => 'http://ex.com/hook')))) && p() && e('1');
r($model->apiCreateHook(1, (object)array('name' => 'test'))) && p() && e('0');
