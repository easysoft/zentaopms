#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->manageBranchPrivs();
cid=1
pid=1

维护已存在的保护分支            >> fail
使用错误的gitlabID维护保护分支  >> success
使用错误的项目ID维护保护分支    >> success

*/

$gitlab = new gitlabTest();

$_POST['branches'][0]    = '1227';
$_POST['mergeLevels'][0] = 40;
$_POST['pushLevels'][0]  = 40;

$result1 = $gitlab->manageBranchPrivsTest(1, 1569);
$result2 = $gitlab->manageBranchPrivsTest(0, 1569);
$result3 = $gitlab->manageBranchPrivsTest(1, 0);

r($result1) && p('') && e('fail');    // 维护已存在的保护分支
r($result2) && p('') && e('success'); // 使用错误的gitlabID维护保护分支
r($result3) && p('') && e('success'); // 使用错误的项目ID维护保护分支
