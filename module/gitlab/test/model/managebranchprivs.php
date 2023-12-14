#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->manageBranchPrivs();
timeout=0
cid=1

- 维护已存在的保护分支 @fail
- 使用错误的gitlabID维护保护分支 @success
- 使用错误的项目ID维护保护分支 @success

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$_POST['name'][0]        = 'protected_branch';
$_POST['mergeAccess'][0] = 40;
$_POST['pushAccess'][0]  = 40;

$result1 = $gitlab->manageBranchPrivsTest(1, 2);
$result2 = $gitlab->manageBranchPrivsTest(0, 2);
$result3 = $gitlab->manageBranchPrivsTest(1, 0);

r($result1) && p('') && e('fail');    // 维护已存在的保护分支
r($result2) && p('') && e('success'); // 使用错误的gitlabID维护保护分支
r($result3) && p('') && e('success'); // 使用错误的项目ID维护保护分支