#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetmergecheckmessage();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apigetmergecheckmessage方法，参数是1, 'feat', 'main'  @1
- 执行model模块的apigetmergecheckmessage方法，参数是1, 'feat', 'main'  @1
- 执行$model->apigetmergecheckmessage(1, 'feat', 'main')) || is_array($model->apigetmergecheckmessage(1, 'feat', 'main')) || is_object($model->apigetmergecheckmessage(1, 'feat', 'main' @1
- 执行model模块的apigetmergecheckmessage方法，参数是1, 'feat', 'main'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apigetmergecheckmessage(1, 'feat', 'main'))) && p() && e('1');
r(!is_null($model->apigetmergecheckmessage(1, 'feat', 'main'))) && p() && e('1');
r(is_bool($model->apigetmergecheckmessage(1, 'feat', 'main')) || is_array($model->apigetmergecheckmessage(1, 'feat', 'main')) || is_object($model->apigetmergecheckmessage(1, 'feat', 'main'))) && p() && e('1');
r(!is_null($model->apigetmergecheckmessage(1, 'feat', 'main'))) && p() && e('1');