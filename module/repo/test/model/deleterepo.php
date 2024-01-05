#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel::createGitlabRepo();
timeout=0
cid=1

- 删除gitlab版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除gitea版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);
zdTable('repohistory')->config('repohistory')->gen(6);
zdTable('repofiles')->config('repofiles')->gen(7);
zdTable('repobranch')->config('repobranch')->gen(2);

$repoTest = new repoTest();
$gitlabID = 1;
$giteaID  = 3;

r($repoTest->deleteRepoTest($gitlabID)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0'); //删除gitlab版本库
r($repoTest->deleteRepoTest($giteaID))  && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0'); //删除gitea版本库