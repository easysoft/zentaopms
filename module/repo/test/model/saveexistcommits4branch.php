#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
su('admin');

/**

title=测试 repoModel->saveExistCommits4Branch();
timeout=0
cid=1

- 保存gitea版本库master分支，主分支为main
 - 第2条的revision属性 @3
 - 第2条的branch属性 @master
- 保存后repobranch表记录数量 @2
- 保存后repobranch表记录数量 @0

*/

$dao->exec('truncate table zt_repohistory');
$dao->exec('truncate table zt_repobranch');

zenData('pipeline')->gen(4);
zenData('repo')->loadYaml('repo')->gen(5);
zenData('repohistory')->loadYaml('repohistory')->gen(6);
zenData('repofiles')->loadYaml('repofiles')->gen(7);
zenData('repobranch')->loadYaml('repobranch')->gen(2);

$repo = new repoTest();

$result = $repo->saveExistCommits4BranchTest(3, 'master');
r($result)        && p('1:revision,branch') && e('3,master'); //保存gitea版本库master分支，主分支为main
r(count($result)) && p()                    && e('2');        //保存后repobranch表记录数量

$result = $repo->saveExistCommits4BranchTest(2, 'main');
r(count($result)) && p() && e('0'); //保存后repobranch表记录数量
