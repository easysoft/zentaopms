#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apideletetag();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apideletetag方法，参数是1, 'v1.0'  @1
- 执行model模块的apideletetag方法，参数是1, 'v1.0'  @1
- 执行$model->apideletetag(1, 'v1.0')) || is_array($model->apideletetag(1, 'v1.0')) || is_object($model->apideletetag(1, 'v1.0' @1
- 执行model模块的apideletetag方法，参数是1, 'v1.0'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apideletetag(1, 'v1.0'))) && p() && e('1');
r(!is_null($model->apideletetag(1, 'v1.0'))) && p() && e('1');
r(is_bool($model->apideletetag(1, 'v1.0')) || is_array($model->apideletetag(1, 'v1.0')) || is_object($model->apideletetag(1, 'v1.0'))) && p() && e('1');
r(!is_null($model->apideletetag(1, 'v1.0'))) && p() && e('1');