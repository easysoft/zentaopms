#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apideletebranch();
timeout=0
cid=0

- 执行 @0
- 执行model模块的apideletebranch方法，参数是1, 'master'  @1
- 执行model模块的apideletebranch方法，参数是1, 'master'  @1
- 执行$model->apideletebranch(1, 'master')) || is_array($model->apideletebranch(1, 'master')) || is_object($model->apideletebranch(1, 'master' @1
- 执行model模块的apideletebranch方法，参数是1, 'master'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiDeleteBranchErrorTest(1, 'master')) && p() && e('1');
r($gitfoxTest->apiDeleteBranchTest(1, 'master')) && p() && e('0');
r($gitfoxTest->apiDeleteBranchResultTypeTest(1, 'master')) && p() && e('bool');
r($gitfoxTest->apiDeleteBranchErrorTest(1, 'master')) && p() && e('1');
r($gitfoxTest->apiDeleteBranchTest(1, 'master')) && p() && e('0');
