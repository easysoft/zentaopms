#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apicreatespace();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apicreatespace方法  @1
- 执行model模块的apicreatespace方法  @1
- 执行$model->apicreatespace((object)array('name'=>'test-space'))) || is_array($model->apicreatespace((object)array('name'=>'test-space'))) || is_object($model->apicreatespace((object)array('name'=>'test-space' @1
- 执行model模块的apicreatespace方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apicreatespace((object)array('name'=>'test-space')))) && p() && e('1');
r(!is_null($model->apicreatespace((object)array('name'=>'test-space')))) && p() && e('1');
r(is_bool($model->apicreatespace((object)array('name'=>'test-space'))) || is_array($model->apicreatespace((object)array('name'=>'test-space'))) || is_object($model->apicreatespace((object)array('name'=>'test-space')))) && p() && e('1');
r(!is_null($model->apicreatespace((object)array('name'=>'test-space')))) && p() && e('1');