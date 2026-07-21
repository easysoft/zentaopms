#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiupdatebranchtype();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apiupdatebranchtype方法，参数是1, 1, array  @1
- 执行model模块的apiupdatebranchtype方法，参数是1, 1, array  @1
- 执行$model->apiupdatebranchtype(1, 1, array('name'=>'fix/*'))) || is_array($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*'))) || is_object($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*' @1
- 执行model模块的apiupdatebranchtype方法，参数是1, 1, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*')))) && p() && e('1');
r(!is_null($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*')))) && p() && e('1');
r(is_bool($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*'))) || is_array($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*'))) || is_object($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*')))) && p() && e('1');
r(!is_null($model->apiupdatebranchtype(1, 1, array('name'=>'fix/*')))) && p() && e('1');