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

$invalidName = (object)array('name' => '', 'space' => 1);
$invalidSpace = (object)array('name' => 'test', 'space' => '');
$valid = (object)array('name' => 'test-repo', 'space' => 1, 'desc' => 'test', 'acl' => 'private', 'product' => 1);

r($gitfoxTest->apiCreateRepoErrorTest($invalidName)) && p() && e('0');
r($gitfoxTest->apiCreateRepoTest($invalidName)) && p() && e('0');
r($gitfoxTest->apiCreateRepoTest($invalidSpace)) && p() && e('0');
r($gitfoxTest->apiCreateRepoErrorTest($valid)) && p() && e('1');
r($gitfoxTest->apiCreateRepoTest($valid)) && p() && e('0');
