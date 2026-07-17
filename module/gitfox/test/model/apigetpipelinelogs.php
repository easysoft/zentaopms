#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetPipelineLogs();
timeout=0
cid=0

- 执行model模块的apiGetPipelineLogs方法，参数是1, 1,   @0
- 执行model模块的apiGetPipelineLogs方法，参数是1, 1,   @0
- 执行model模块的apiGetPipelineLogs方法，参数是1, 1,   @1
- 执行 @0
- 执行model模块的apiGetPipelineLogs方法，参数是1, 1,   @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r($model->apiGetPipelineLogs(1, 1, (object)array('name' => '', 'number' => 1))) && p() && e('0');
r($model->apiGetPipelineLogs(1, 1, (object)array('name' => 'build', 'number' => ''))) && p() && e('0');
r(is_string($model->apiGetPipelineLogs(1, 1, (object)array('name' => 'build', 'number' => 1)))) && p() && e('1');
r((int)dao::isError()) && p() && e('0');
r(is_string($model->apiGetPipelineLogs(1, 1, (object)array('name' => 'build', 'number' => 1)))) && p() && e('1');