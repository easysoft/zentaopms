#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->updateCommitDate();
timeout=0
cid=1

- 更新gitlab版本库属性lastCommit @2023-12-23 11:39:02
- 更新gitea版本库属性lastCommit @~~
- 更新空版本库 @return empty

*/

zdTable('repo')->config('repo')->gen(5);

$repo = new repoTest();

$gitlabID = 1;
$giteaID  = 3;

r($repo->updateCommitDateTest($gitlabID)) && p('lastCommit') && e('2023-12-23 11:39:02'); //更新gitlab版本库
r($repo->updateCommitDateTest($giteaID))  && p('lastCommit') && e('~~'); //更新gitea版本库
r($repo->updateCommitDateTest(0))         && p()             && e('return empty'); //更新空版本库