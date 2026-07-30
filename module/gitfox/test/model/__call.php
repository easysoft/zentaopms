#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::__call();
timeout=0
cid=0

- 执行model模块的apiGetSingleRepo方法，参数是7 属性name @cached-magic
- 执行model模块的apiGetSingleProject方法，参数是7 属性name @cached-magic
- 执行model模块的thisMethodDoesNotExist方法，参数是1  @0
- 执行 @0
- 执行model模块的APIgetSINGLErepo方法，参数是7 属性name @cached-magic

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$cached = new stdclass();
$cached->id   = 7;
$cached->name = 'cached-magic';
$gitfoxTest->setRepoCache(7, $cached);

r($model->apiGetSingleRepo(7)) && p('name') && e('cached-magic');
r($model->apiGetSingleProject(7)) && p('name') && e('cached-magic');
r($model->thisMethodDoesNotExist(1)) && p() && e('0');
r($model->APIgetSINGLErepo(7)) && p('name') && e('cached-magic');
r($gitfoxTest->apiGetSingleRepoTypeTest(7)) && p() && e('object');
