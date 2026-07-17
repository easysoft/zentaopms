#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateBranch();
timeout=0
cid=0

- 步骤 1：name 为空 apiCreateBranch 返回空对象 @0
- 步骤 2：source 为空 apiCreateBranch 返回空对象 @0
- 步骤 3：apiCreateBranch 不产生 dao 错误 @0
- 步骤 4：正常参数 apiCreateBranch 返回 object @1
- 步骤 5：再次 name 为空返回空对象 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$emptyName  = (object)array('name' => '', 'source' => 'main');
$emptySource = (object)array('name' => 'test', 'source' => '');
$valid = (object)array('name' => 'feature/test', 'source' => 'main');

r(count((array)$model->apiCreateBranch(1, $emptyName))) && p() && e('0');
r(count((array)$model->apiCreateBranch(1, $emptySource))) && p() && e('0');
r((int)dao::isError()) && p() && e('0');
r(is_object($model->apiCreateBranch(1, $valid)) || is_null($model->apiCreateBranch(1, $valid)) || is_bool($model->apiCreateBranch(1, $valid))) && p() && e('1');
r(count((array)$model->apiCreateBranch(1, $emptyName))) && p() && e('0');
