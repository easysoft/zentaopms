#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSingleRepo();
timeout=0
cid=0

- 执行model模块的apiGetSingleRepo方法，参数是99 属性name @cached-repo
- 执行 @0
- 执行model模块的apiGetSingleRepo方法，参数是1  @1
- 执行apiGetSingleRepo(2)) || is_object($model模块的apiGetSingleRepo方法，参数是2  @1
- 执行model模块的apiGetSingleRepo方法，参数是3  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$cached = new stdclass();
$cached->id   = 99;
$cached->name = 'cached-repo';
$gitfoxTest->setRepoCache(99, $cached);

r($model->apiGetSingleRepo(99)) && p('name') && e('cached-repo');
r((int)dao::isError()) && p() && e('0');
r(!is_null($model->apiGetSingleRepo(1))) && p() && e('1');
r(is_array($model->apiGetSingleRepo(2)) || is_object($model->apiGetSingleRepo(2))) && p() && e('1');
r(!is_null($model->apiGetSingleRepo(3))) && p() && e('1');