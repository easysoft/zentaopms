#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::request();
timeout=0
cid=0

- 执行model模块的request方法，参数是'/test', 'GET'  @1
- 执行 @0
- 执行model模块的request方法，参数是'/test'  @0
- 执行model模块的request方法，参数是'/test'  @1
- 执行model模块的request方法，参数是'/test'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

r(!is_null($model->request('/test', 'GET'))) && p() && e('1');
r((int)dao::isError()) && p() && e('0');
zenData('entry')->gen(0);
r($model->request('/test')) && p() && e('0');
zenData('entry')->loadYaml('entry')->gen(1);
r(!is_null($model->request('/test'))) && p() && e('1');
r(!is_null($model->request('/test'))) && p() && e('1');