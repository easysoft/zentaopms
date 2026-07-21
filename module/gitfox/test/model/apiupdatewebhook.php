#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiupdatewebhook();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apiupdatewebhook方法，参数是1, 1,   @1
- 执行model模块的apiupdatewebhook方法，参数是1, 1,   @1
- 执行$model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h'))) || is_array($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h'))) || is_object($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h' @1
- 执行model模块的apiupdatewebhook方法，参数是1, 1,   @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h')))) && p() && e('1');
r(!is_null($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h')))) && p() && e('1');
r(is_bool($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h'))) || is_array($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h'))) || is_object($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h')))) && p() && e('1');
r(!is_null($model->apiupdatewebhook(1, 1, (object)array('url'=>'http://ex.com/h')))) && p() && e('1');