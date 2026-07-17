#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetspaces();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apigetspaces方法，参数是array  @1
- 执行model模块的apigetspaces方法，参数是array  @1
- 执行$model->apigetspaces(array())) || is_array($model->apigetspaces(array())) || is_object($model->apigetspaces(array( @1
- 执行model模块的apigetspaces方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apigetspaces(array()))) && p() && e('1');
r(!is_null($model->apigetspaces(array()))) && p() && e('1');
r(is_bool($model->apigetspaces(array())) || is_array($model->apigetspaces(array())) || is_object($model->apigetspaces(array()))) && p() && e('1');
r(!is_null($model->apigetspaces(array()))) && p() && e('1');