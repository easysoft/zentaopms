#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetrepos();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apigetrepos方法  @1
- 执行model模块的apigetrepos方法  @1
- 执行$model->apigetrepos()) || is_array($model->apigetrepos()) || is_object($model->apigetrepos( @1
- 执行model模块的apigetrepos方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apigetrepos())) && p() && e('1');
r(!is_null($model->apigetrepos())) && p() && e('1');
r(is_bool($model->apigetrepos()) || is_array($model->apigetrepos()) || is_object($model->apigetrepos())) && p() && e('1');
r(!is_null($model->apigetrepos())) && p() && e('1');