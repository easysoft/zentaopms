#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveOneCommit();
timeout=0
cid=1

- 保存版本库1 一条commit
 - 属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
 - 属性commit @1
- version初始为6保存版本库1 一条commit
 - 属性revision @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
 - 属性commit @6
- 指定分支名保存版本库1 一条commit
 - 属性repo @1
 - 属性branch @branch1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(4);

$repo = new repoTest();

$repoID  = 1;
$version = 1;

r($repo->saveOneCommitTest($repoID, $version)) && p('revision,commit') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a,1'); //保存版本库1 一条commit
$version = 6;
r($repo->saveOneCommitTest($repoID, $version)) && p('revision,commit') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a,6'); //version初始为6保存版本库1 一条commit
$branch = 'branch1';
r($repo->saveOneCommitTest($repoID, $version, $branch)) && p('repo,branch') && e('1,branch1'); //指定分支名保存版本库1 一条commit