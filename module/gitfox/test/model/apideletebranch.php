#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apideletebranch();
timeout=0
cid=0

- 执行 @1
- 执行model模块的apideletebranch方法，参数是1, 默认分支 @0
- 执行model模块的apideletebranch方法，参数是1, 默认分支 @1
- 执行$model->apideletebranch(1, 默认分支) || is_array($model->apideletebranch(1, 默认分支)) || is_object($model->apideletebranch(1, 默认分支 @1
- 执行model模块的apideletebranch方法，参数是1, 默认分支 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

$repo   = $gitfoxTest->apiGetSingleRepoTest(1);
$branch = is_object($repo) && !empty($repo->defaultBranch) ? $repo->defaultBranch : (is_object($repo) && !empty($repo->default_branch) ? $repo->default_branch : 'master');

r(in_array($gitfoxTest->apiDeleteBranchErrorTest(1, $branch), array(0, 1))) && p() && e('1');
r($gitfoxTest->apiDeleteBranchTest(1, $branch)) && p() && e('0');
r($gitfoxTest->apiDeleteBranchResultTypeTest(1, $branch)) && p() && e('bool');
r(in_array($gitfoxTest->apiDeleteBranchErrorTest(1, $branch), array(0, 1))) && p() && e('1');
r($gitfoxTest->apiDeleteBranchTest(1, $branch)) && p() && e('0');
