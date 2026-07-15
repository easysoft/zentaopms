#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiUpdateRepo();
timeout=0
cid=0

- 执行 @0
- 执行$r) || is_bool($r) || is_array($r) || is_object($r @1
- 执行$r) || is_bool($r) || is_array($r) || is_object($r @1
- 执行$r) || is_bool($r) || is_array($r) || is_object($r @1
- 执行$r) || is_bool($r) || is_array($r) || is_object($r @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$repo = (object)array('name' => 'test', 'defaultBranch' => 'main', 'desc' => 'test', 'acl' => 'private', 'space' => 1, 'product' => 1);

r((int)dao::isError()) && p() && e('0');
$r = $model->apiUpdateRepo(1, $repo);
r(is_null($r) || is_bool($r) || is_array($r) || is_object($r)) && p() && e('1');
r(is_null($r) || is_bool($r) || is_array($r) || is_object($r)) && p() && e('1');
$r = $model->apiUpdateRepo(1, $repo);
r(is_null($r) || is_bool($r) || is_array($r) || is_object($r)) && p() && e('1');
$r = $model->apiUpdateRepo(1, $repo);
r(is_null($r) || is_bool($r) || is_array($r) || is_object($r)) && p() && e('1');