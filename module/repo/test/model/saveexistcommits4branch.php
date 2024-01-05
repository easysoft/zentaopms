#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->saveExistCommits4Branch();
timeout=0
cid=1

- 保存gitea版本库master分支，主分支为main
 - 第2条的revision属性 @3
 - 第2条的branch属性 @master
- 保存后repobranch表记录数量 @3

*/

$dao->exec('truncate table zt_repohistory');
$dao->exec('truncate table zt_repobranch');

zdTable('pipeline')->gen(4);
zdTable('repo')->config('repo')->gen(5);
zdTable('repohistory')->config('repohistory')->gen(6);
zdTable('repofiles')->config('repofiles')->gen(7);
zdTable('repobranch')->config('repobranch')->gen(2);

$repo = new repoTest();

$giteaID = 3;
$branch  = 'master';

$result = $repo->saveExistCommits4BranchTest($giteaID, $branch);
r($result)        && p('2:revision,branch') && e('3,master'); //保存gitea版本库master分支，主分支为main
r(count($result)) && p()                    && e('3'); //保存后repobranch表记录数量
