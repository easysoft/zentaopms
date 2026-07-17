#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apideletebranchtype();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apideletebranchtype方法，参数是1, 1  @1
- 执行model模块的apideletebranchtype方法，参数是1, 1  @1
- 执行$model->apideletebranchtype(1, 1)) || is_array($model->apideletebranchtype(1, 1)) || is_object($model->apideletebranchtype(1, 1 @1
- 执行model模块的apideletebranchtype方法，参数是1, 1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apideletebranchtype(1, 1))) && p() && e('1');
r(!is_null($model->apideletebranchtype(1, 1))) && p() && e('1');
r(is_bool($model->apideletebranchtype(1, 1)) || is_array($model->apideletebranchtype(1, 1)) || is_object($model->apideletebranchtype(1, 1))) && p() && e('1');
r(!is_null($model->apideletebranchtype(1, 1))) && p() && e('1');