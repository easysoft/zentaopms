#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->setMenu();
timeout=0
cid=1

- 正常设置版本库id @2
- 设置不存在版本库id @1
- 无权限用户设置版本库id @1

*/

zdTable('user')->gen(20);
zdTable('pipeline')->gen(5);
zdTable('project')->gen(5);
zdTable('oauth')->config('oauth')->gen(20);
$repo = zdTable('repo')->config('repo');
$repo->acl->range('[{"acl":"private"}]');
$repo->product->range('1,1,1000');
$repo->gen(4);

$repo = new repoTest();

$repoID = 2;
r($repo->setMenuTest($repoID)) && p() && e('2'); //正常设置版本库id
r($repo->setMenuTest(10001))   && p() && e('1'); //设置不存在版本库id

su('user19');
$repoID = 3;
$result = $repo->setMenuTest($repoID);
r(strpos($result, '你没有权限访问该代码库') !== false) && p() && e('1'); //无权限用户设置版本库id
