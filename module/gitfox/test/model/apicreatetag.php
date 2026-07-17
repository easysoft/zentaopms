#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateTag();
timeout=0
cid=0

- 执行model模块的apiCreateTag方法，参数是1, $emptyName  @0
- 执行model模块的apiCreateTag方法，参数是1, $emptySource  @0
- 执行 @0
- 执行model模块的apiCreateTag方法，参数是1, $valid  @1
- 执行model模块的apiCreateTag方法，参数是1, $emptyName  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$emptyName  = (object)array('name' => '', 'source' => 'main');
$emptySource = (object)array('name' => 'v1.0', 'source' => '');
$valid = (object)array('name' => 'v1.0', 'source' => 'main');

r($model->apiCreateTag(1, $emptyName)) && p() && e('0');
r($model->apiCreateTag(1, $emptySource)) && p() && e('0');
r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apiCreateTag(1, $valid))) && p() && e('1');
r($model->apiCreateTag(1, $emptyName)) && p() && e('0');