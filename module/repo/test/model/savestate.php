#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveState();
timeout=0
cid=1

- 设置代码库id @2
- 设置不存在代码库id @1
- project tab下设置代码库id @1

*/

zdTable('repo')->config('repo')->gen(4);

$repo = new repoTest();

$repoID    = 2;
$projectID = 11;

r($repo->saveStateTest($repoID)) && p() && e('2'); //设置代码库id
r($repo->saveStateTest(10001))   && p() && e('1'); //设置不存在代码库id

$repo->objectModel->app->tab = 'project';
r($repo->saveStateTest($repoID, $projectID)) && p() && e('1'); //project tab下设置代码库id