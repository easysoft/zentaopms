#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateRepo();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apiCreateRepo方法  @0
- 执行model模块的apiCreateRepo方法  @0
- 执行model模块的apiCreateRepo方法，参数是$valid  @1
- 执行apiCreateRepo($valid)) || is_object($model模块的apiCreateRepo方法，参数是$valid  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r($model->apiCreateRepo((object)array('name' => '', 'space' => 1))) && p() && e('0');
r($model->apiCreateRepo((object)array('name' => 'test', 'space' => ''))) && p() && e('0');
$valid = (object)array('name' => 'test-repo', 'space' => 1, 'desc' => 'test', 'acl' => 'private', 'product' => 1);
r(!is_null($model->apiCreateRepo($valid))) && p() && e('1');
r(is_bool($model->apiCreateRepo($valid)) || is_object($model->apiCreateRepo($valid))) && p() && e('1');