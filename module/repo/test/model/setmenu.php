#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->setMenu();
timeout=0
cid=18104

- 正常设置版本库id @2
- 正常设置版本库id @3
- 正常设置版本库id @4
- 设置不存在版本库id @1
- 无权限用户设置版本库id @0

*/

zenData('user')->gen(20);
zenData('pipeline')->gen(5);
zenData('project')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(20);
$repo = zenData('repo')->loadYaml('repo');
$repo->acl->range('[{"acl":"private"}]');
$repo->product->range('1,1,1000');
$repo->gen(4);

$repo = new repoTest();

$repoID = 2;
r($repo->setMenuTest($repoID)) && p() && e('2'); //正常设置版本库id
r($repo->setMenuTest(3))       && p() && e('3'); //正常设置版本库id
r($repo->setMenuTest(4))       && p() && e('4'); //正常设置版本库id
r($repo->setMenuTest(10001))   && p() && e('1'); //设置不存在版本库id

su('user19');
$repoID = 3;
r($repo->setMenuTest($repoID)) && p() && e('0'); //无权限用户设置版本库id
