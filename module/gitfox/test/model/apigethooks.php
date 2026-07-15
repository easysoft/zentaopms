#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigethooks();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apigethooks方法，参数是1  @1
- 执行model模块的apigethooks方法，参数是1  @1
- 执行$model->apigethooks(1)) || is_array($model->apigethooks(1)) || is_object($model->apigethooks(1 @1
- 执行model模块的apigethooks方法，参数是1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apigethooks(1))) && p() && e('1');
r(!is_null($model->apigethooks(1))) && p() && e('1');
r(is_bool($model->apigethooks(1)) || is_array($model->apigethooks(1)) || is_object($model->apigethooks(1))) && p() && e('1');
r(!is_null($model->apigethooks(1))) && p() && e('1');