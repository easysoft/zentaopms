#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiupdatespace();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apiupdatespace方法，参数是1,   @1
- 执行model模块的apiupdatespace方法，参数是1,   @1
- 执行$model->apiupdatespace(1, (object)array('name'=>'updated'))) || is_array($model->apiupdatespace(1, (object)array('name'=>'updated'))) || is_object($model->apiupdatespace(1, (object)array('name'=>'updated' @1
- 执行model模块的apiupdatespace方法，参数是1,   @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apiupdatespace(1, (object)array('name'=>'updated')))) && p() && e('1');
r(!is_null($model->apiupdatespace(1, (object)array('name'=>'updated')))) && p() && e('1');
r(is_bool($model->apiupdatespace(1, (object)array('name'=>'updated'))) || is_array($model->apiupdatespace(1, (object)array('name'=>'updated'))) || is_object($model->apiupdatespace(1, (object)array('name'=>'updated')))) && p() && e('1');
r(!is_null($model->apiupdatespace(1, (object)array('name'=>'updated')))) && p() && e('1');